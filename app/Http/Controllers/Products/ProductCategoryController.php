<?php

namespace App\Http\Controllers\Products;
use App\Http\Controllers\Controller;
use App\Models\ProductCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ProductCategoryController extends Controller
{
    /**
     * Inertia index that supports:
     * - active paginator always
     * - inactive drawer paginator only when inactive_drawer=1
     * - search/sort/pagination via query string
     */
    public function index(Request $request)
    {
        $user = $request->user();

        // If your user stores branch differently, change this.
        $branchId = $user->branch_id ?? null;

        // Basic query params (active list)
        $search = trim((string) $request->input('search', ''));
        $perPage = (int) $request->input('per_page', 20);
        $page = (int) $request->input('page', 1);

        // Sorting
        $sort = (string) $request->input('sort', 'id');
        $dir = strtolower((string) $request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        // Anchor params / filters (optional). You can whitelist.
        // Example: status/type/etc can come here: $request->input('status'), etc.
        // We'll just keep query string safe by whitelisting sortable fields.
        $sortable = ['id', 'name', 'active', 'created_at', 'updated_at'];
        if (!in_array($sort, $sortable, true)) $sort = 'id';

        // Active list base query (branch scoped)
        $base = ProductCategory::query()
            ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId));

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        // Always return ACTIVE paginator (active=1)
        $items = (clone $base)
            ->where('active', 1)
            ->orderBy($sort, $dir)
            ->paginate($perPage, ['*'], 'page', $page)
            ->withQueryString();

        // Optional: include inactive list only when drawer is opened
        $inactiveItems = null;
        $inactiveDrawer = (int) $request->input('inactive_drawer', 0) === 1;

        if ($inactiveDrawer) {
            $inactiveSearch = trim((string) $request->input('inactive_search', ''));
            $inactivePage = (int) $request->input('inactive_page', 1);
            $inactivePerPage = (int) $request->input('inactive_per_page', 10);

            $inactiveQ = ProductCategory::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->where('active', 0);

            if ($inactiveSearch !== '') {
                $inactiveQ->where(function ($q) use ($inactiveSearch) {
                    $q->where('name', 'like', "%{$inactiveSearch}%")
                      ->orWhere('description', 'like', "%{$inactiveSearch}%");
                });
            }

            // NOTE: Use a different page param name to avoid collision
            $inactiveItems = $inactiveQ
                ->orderBy($sort, $dir)
                ->paginate($inactivePerPage, ['*'], 'inactive_page', $inactivePage)
                ->withQueryString();
        }

        return Inertia::render('ProductCategories/Index', [
            'items' => $items,
            'inactiveItems' => $inactiveItems,
            'query' => $request->all(),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
        ]);

        $data['active'] = array_key_exists('active', $data) ? (bool)$data['active'] : true;

        // Set branch_id and user_add_id automatically if your table has them.
        if ($branchId !== null) $data['branch_id'] = $branchId;
        if (schema_has_column('product_categories', 'user_add_id')) {
            $data['user_add_id'] = $user->id;
        } else {
            // If you still have it in model but not sure, ignore.
            $data['user_add_id'] = $user->id;
        }

        ProductCategory::create($data);

        return back();
    }

    public function update(Request $request, ProductCategory $productCategory)
    {
        $this->assertBranchScope($request, $productCategory);

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
        ]);

        if (array_key_exists('active', $data)) $data['active'] = (bool)$data['active'];

        $productCategory->update($data);

        return back();
    }

    public function destroy(Request $request, ProductCategory $productCategory)
    {
        $this->assertBranchScope($request, $productCategory);

        // Permanent delete (matches your "Delete Permanently")
        $productCategory->delete();

        return back();
    }

    /**
     * Bulk endpoint for:
     * - inactivate / activate
     * - update (set fields)
     * - import (bulk create)
     */
    public function bulk(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $op = (string) $request->input('op', '');

        if ($op === 'inactivate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            ProductCategory::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->whereIn('id', $payload['ids'])
                ->update(['active' => 0]);

            return back();
        }

        if ($op === 'activate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            ProductCategory::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->whereIn('id', $payload['ids'])
                ->update(['active' => 1]);

            return back();
        }

        if ($op === 'update') {
            $payload = $request->validate([
                'ids' => ['required', 'array'],
                'ids.*' => ['integer'],
                'actions' => ['required', 'array'],
            ]);

            // Safety: allow only known fields to update
            $allowed = ['active', 'description'];
            $actions = array_intersect_key($payload['actions'], array_flip($allowed));

            ProductCategory::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->whereIn('id', $payload['ids'])
                ->update($actions);

            return back();
        }

        if ($op === 'import') {
            $payload = $request->validate([
                'rows' => ['required', 'array'],
            ]);

            DB::transaction(function () use ($payload, $branchId, $user) {
                foreach ($payload['rows'] as $row) {
                    // normalize row keys from excel
                    $name = trim((string)($row['name'] ?? $row['Name'] ?? ''));
                    if ($name === '') continue;

                    ProductCategory::create([
                        'branch_id' => $branchId,
                        'name' => $name,
                        'description' => (string)($row['description'] ?? $row['Description'] ?? ''),
                        'active' => isset($row['active']) ? (bool)$row['active'] : true,
                        'user_add_id' => $user->id,
                    ]);
                }
            });

            return back();
        }

        abort(400, 'Unknown bulk op');
    }

    /**
     * Enforce branch-scope on route model binding operations.
     * If user has no branch_id, you can adjust logic accordingly.
     */
    private function assertBranchScope(Request $request, ProductCategory $record): void
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        // If branch_id is mandatory in your system, enforce:
        if ($branchId !== null && (int)$record->branch_id !== (int)$branchId) {
            abort(403, 'Forbidden (branch scope)');
        }
    }
}

/**
 * Tiny helper to avoid fatal if you don't have schema helper.
 * You can delete this and just set user_add_id always.
 */
if (!function_exists('schema_has_column')) {
    function schema_has_column(string $table, string $column): bool
    {
        try {
            return \Illuminate\Support\Facades\Schema::hasColumn($table, $column);
        } catch (\Throwable $e) {
            return false;
        }
    }
}
