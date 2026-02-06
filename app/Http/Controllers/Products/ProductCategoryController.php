<?php
namespace App\Http\Controllers\Products;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use App\Support\BranchContext;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductCategoryController extends Controller
{
    public function index(Request $request)
    {
        $branchId = BranchContext::requireId();

        $q = trim((string) $request->query('q', ''));
        $sort = $request->query('sort', 'created_at');
        $dir = strtolower($request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $filters = [
            'active' => $request->query('active'),
            'parent_id' => $request->query('parent_id'),
        ];

        $allowedSorts = ['created_at', 'name', 'active'];

        $query = ProductCategory::query()
            ->where('branch_id', $branchId);

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('description', 'like', "%{$q}%");
            });
        }

        if ($filters['active'] !== null && $filters['active'] !== '') {
            $query->where('active', (bool)((int)$filters['active']));
        }

        if (!empty($filters['parent_id'])) {
            $query->where('parent_id', $filters['parent_id']);
        }

        if (!in_array($sort, $allowedSorts, true)) $sort = 'created_at';

        $categories = $query
            ->orderBy($sort, $dir)
            ->paginate((int) $request->query('per_page', 15))
            ->withQueryString();

        // parent dropdown options (branch-scoped)
        $parentOptions = ProductCategory::query()
            ->where('branch_id', $branchId)
            ->select('id', 'name')
            ->orderBy('name')
            ->get();

        return Inertia::render('ProductCategories/Index', [
            'categories' => $categories,
            'parents' => $parentOptions,
            'query' => [
                'q' => $q,
                'sort' => $sort,
                'dir' => $dir,
                'per_page' => (int) $request->query('per_page', 15),
                ...$filters,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $branchId = BranchContext::requireId();

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'uuid', 'exists:product_categories,id'],
            'description' => ['nullable', 'string'],
            'active' => ['boolean'],
        ]);

        // enforce branch scope safely (ignore client value)
        $data['branch_id'] = $branchId;
        $data['user_add_id'] = $request->user()->id;

        // parent must also be in same branch
        if (!empty($data['parent_id'])) {
            $ok = ProductCategory::where('id', $data['parent_id'])
                ->where('branch_id', $branchId)
                ->exists();
            abort_if(!$ok, 422, 'Parent category must be in your branch.');
        }

        ProductCategory::create($data);

        return back()->with('success', 'Category created.');
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $branchId = BranchContext::requireId();
        abort_if($productCategory->branch_id !== $branchId, 403);

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'parent_id' => ['nullable', 'uuid', 'exists:product_categories,id'],
            'description' => ['nullable', 'string'],
            'active' => ['boolean'],
        ]);

        // prevent cross-branch parent + self-parent
        if (!empty($data['parent_id'])) {
            abort_if($data['parent_id'] === $productCategory->id, 422, 'Category cannot be its own parent.');

            $ok = ProductCategory::where('id', $data['parent_id'])
                ->where('branch_id', $branchId)
                ->exists();
            abort_if(!$ok, 422, 'Parent category must be in your branch.');
        }

        $productCategory->update($data);

        return back()->with('success', 'Category updated.');
    }

    public function destroy(ProductCategory $productCategory)
    {
        $branchId = BranchContext::requireId();
        abort_if($productCategory->branch_id !== $branchId, 403);

        $productCategory->delete();

        return back()->with('success', 'Category deleted.');
    }

    // ---------- BULK ----------

    public function bulkStore(Request $request)
    {
        $branchId = BranchContext::requireId();

        $payload = $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:500'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.parent_id' => ['nullable', 'uuid'],
            'items.*.description' => ['nullable', 'string'],
            'items.*.active' => ['nullable', 'boolean'],
        ]);

        // validate all parent_id belong to same branch (if provided)
        $parentIds = collect($payload['items'])->pluck('parent_id')->filter()->unique()->values();
        if ($parentIds->isNotEmpty()) {
            $count = ProductCategory::where('branch_id', $branchId)->whereIn('id', $parentIds)->count();
            abort_if($count !== $parentIds->count(), 422, 'One or more parent categories are not in your branch.');
        }

        $rows = collect($payload['items'])->map(function ($i) use ($branchId, $request) {
            return [
                'id' => null, // HasUuids handles on create; but insert bypasses model events.
                'branch_id' => $branchId,
                'name' => $i['name'],
                'parent_id' => $i['parent_id'] ?? null,
                'description' => $i['description'] ?? null,
                'user_add_id' => $request->user()->id,
                'active' => (bool)($i['active'] ?? true),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();

        // IMPORTANT: insert() bypasses UUID generation.
        // So we must generate UUIDs ourselves:
        foreach ($rows as &$r) {
            $r['id'] = (string) \Illuminate\Support\Str::uuid();
        }

        ProductCategory::insert($rows);

        return back()->with('success', 'Bulk categories created.');
    }

    public function bulkUpdate(Request $request)
    {
        $branchId = BranchContext::requireId();

        $payload = $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:500'],
            'items.*.id' => ['required', 'uuid', 'exists:product_categories,id'],
            'items.*.name' => ['sometimes', 'string', 'max:255'],
            'items.*.parent_id' => ['sometimes', 'nullable', 'uuid'],
            'items.*.description' => ['sometimes', 'nullable', 'string'],
            'items.*.active' => ['sometimes', 'boolean'],
        ]);

        // ensure all ids belong to branch
        $ids = collect($payload['items'])->pluck('id')->unique()->values();
        $count = ProductCategory::where('branch_id', $branchId)->whereIn('id', $ids)->count();
        abort_if($count !== $ids->count(), 403, 'Some categories are not in your branch.');

        // ensure all parent_id (if any) belong to branch
        $parentIds = collect($payload['items'])->pluck('parent_id')->filter()->unique()->values();
        if ($parentIds->isNotEmpty()) {
            $pcount = ProductCategory::where('branch_id', $branchId)->whereIn('id', $parentIds)->count();
            abort_if($pcount !== $parentIds->count(), 422, 'One or more parent categories are not in your branch.');
        }

        foreach ($payload['items'] as $item) {
            $id = $item['id'];
            unset($item['id']);

            // prevent self parent if provided
            if (array_key_exists('parent_id', $item) && $item['parent_id'] === $id) {
                abort(422, 'A category cannot be its own parent.');
            }

            $item['updated_at'] = now();

            ProductCategory::where('branch_id', $branchId)
                ->where('id', $id)
                ->update($item);
        }

        return back()->with('success', 'Bulk categories updated.');
    }

    public function bulkDestroy(Request $request)
    {
        $branchId = BranchContext::requireId();

        $payload = $request->validate([
            'ids' => ['required', 'array', 'min:1', 'max:1000'],
            'ids.*' => ['required', 'uuid', 'exists:product_categories,id'],
        ]);

        $ids = collect($payload['ids'])->unique()->values();

        // ensure belong to branch
        $count = ProductCategory::where('branch_id', $branchId)->whereIn('id', $ids)->count();
        abort_if($count !== $ids->count(), 403, 'Some categories are not in your branch.');

        ProductCategory::where('branch_id', $branchId)->whereIn('id', $ids)->delete();

        return back()->with('success', 'Bulk categories deleted.');
    }
}
