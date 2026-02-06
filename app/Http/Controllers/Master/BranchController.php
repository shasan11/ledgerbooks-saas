<?php
namespace App\Http\Controllers\Master;
use App\Http\Controllers\Controller;

use App\Models\Branch;
use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $sort = $request->query('sort', 'created_at');
        $dir = strtolower($request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $filters = [
            'active' => $request->query('active'), // 1/0/null
            'is_head_office' => $request->query('is_head_office'),
            'country' => $request->query('country'),
            'city' => $request->query('city'),
            'currency_id' => $request->query('currency_id'),
        ];

        $allowedSorts = ['created_at', 'name', 'code', 'country', 'city', 'active', 'is_head_office'];

        $query = Branch::query();

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('name', 'like', "%{$q}%")
                   ->orWhere('code', 'like', "%{$q}%")
                   ->orWhere('email', 'like', "%{$q}%")
                   ->orWhere('phone', 'like', "%{$q}%")
                   ->orWhere('country', 'like', "%{$q}%")
                   ->orWhere('city', 'like', "%{$q}%");
            });
        }

        // Filters
        if ($filters['active'] !== null && $filters['active'] !== '') {
            $query->where('active', (bool) ((int) $filters['active']));
        }
        if ($filters['is_head_office'] !== null && $filters['is_head_office'] !== '') {
            $query->where('is_head_office', (bool) ((int) $filters['is_head_office']));
        }
        if (!empty($filters['country'])) {
            $query->where('country', $filters['country']);
        }
        if (!empty($filters['city'])) {
            $query->where('city', $filters['city']);
        }
        if (!empty($filters['currency_id'])) {
            $query->where('currency_id', (int) $filters['currency_id']);
        }

        if (!in_array($sort, $allowedSorts, true)) {
            $sort = 'created_at';
        }

        $branches = $query
            ->orderBy($sort, $dir)
            ->paginate((int) $request->query('per_page', 15))
            ->withQueryString();

        return Inertia::render('Branches/Index', [
            'branches' => $branches,
            'query' => [
                'q' => $q,
                'sort' => $sort,
                'dir' => $dir,
                'per_page' => (int) $request->query('per_page', 15),
                ...$filters,
            ],
            'meta' => [
                'countries' => Branch::query()->whereNotNull('country')->distinct()->orderBy('country')->pluck('country'),
                'cities' => Branch::query()->whereNotNull('city')->distinct()->orderBy('city')->pluck('city'),
                'currencies' => Currency::query()->select('id', 'code', 'name')->orderBy('code')->get(),
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateBranch($request);

        Branch::create($data);

        return back()->with('success', 'Branch created.');
    }

    public function update(Request $request, Branch $branch)
    {
        $data = $this->validateBranch($request, $branch->id);

        $branch->update($data);

        return back()->with('success', 'Branch updated.');
    }

    public function destroy(Branch $branch)
    {
        $branch->delete();
        return back()->with('success', 'Branch deleted.');
    }

    // ---------- BULK ----------

    // Bulk create: { items: [ {branch fields}, ... ] }
    public function bulkStore(Request $request)
    {
        $payload = $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:500'],
            'items.*.code' => ['nullable', 'string', 'max:50'],
            'items.*.name' => ['required', 'string', 'max:255'],
            'items.*.email' => ['nullable', 'email', 'max:255'],
            'items.*.phone' => ['nullable', 'string', 'max:50'],
            'items.*.address' => ['nullable', 'string', 'max:255'],
            'items.*.country' => ['nullable', 'string', 'max:100'],
            'items.*.city' => ['nullable', 'string', 'max:100'],
            'items.*.timezone' => ['nullable', 'string', 'max:100'],
            'items.*.currency_id' => ['nullable', 'integer', 'exists:currencies,id'],
            'items.*.is_head_office' => ['nullable', 'boolean'],
            'items.*.active' => ['nullable', 'boolean'],
        ]);

        $rows = collect($payload['items'])->map(function ($i) {
            return [
                'code' => $i['code'] ?? null,
                'name' => $i['name'],
                'email' => $i['email'] ?? null,
                'phone' => $i['phone'] ?? null,
                'address' => $i['address'] ?? null,
                'country' => $i['country'] ?? null,
                'city' => $i['city'] ?? null,
                'timezone' => $i['timezone'] ?? null,
                'currency_id' => $i['currency_id'] ?? null,
                'is_head_office' => (bool)($i['is_head_office'] ?? false),
                'active' => (bool)($i['active'] ?? true),
                'created_at' => now(),
                'updated_at' => now(),
            ];
        })->all();

        Branch::insert($rows);

        return back()->with('success', 'Branches bulk created.');
    }

    // Bulk update: { items: [ {id, ...fieldsToUpdate}, ... ] }
    public function bulkUpdate(Request $request)
    {
        $payload = $request->validate([
            'items' => ['required', 'array', 'min:1', 'max:500'],
            'items.*.id' => ['required', 'uuid', 'exists:branches,id'],

            // allow partial updates
            'items.*.code' => ['sometimes', 'nullable', 'string', 'max:50'],
            'items.*.name' => ['sometimes', 'required', 'string', 'max:255'],
            'items.*.email' => ['sometimes', 'nullable', 'email', 'max:255'],
            'items.*.phone' => ['sometimes', 'nullable', 'string', 'max:50'],
            'items.*.address' => ['sometimes', 'nullable', 'string', 'max:255'],
            'items.*.country' => ['sometimes', 'nullable', 'string', 'max:100'],
            'items.*.city' => ['sometimes', 'nullable', 'string', 'max:100'],
            'items.*.timezone' => ['sometimes', 'nullable', 'string', 'max:100'],
            'items.*.currency_id' => ['sometimes', 'nullable', 'integer', 'exists:currencies,id'],
            'items.*.is_head_office' => ['sometimes', 'boolean'],
            'items.*.active' => ['sometimes', 'boolean'],
        ]);

        foreach ($payload['items'] as $item) {
            $id = $item['id'];
            unset($item['id']);

            $item['updated_at'] = now();

            Branch::whereKey($id)->update($item);
        }

        return back()->with('success', 'Branches bulk updated.');
    }

    // Bulk delete: { ids: ["uuid", ...] }
    public function bulkDestroy(Request $request)
    {
        $payload = $request->validate([
            'ids' => ['required', 'array', 'min:1', 'max:1000'],
            'ids.*' => ['required', 'uuid', 'exists:branches,id'],
        ]);

        Branch::whereIn('id', $payload['ids'])->delete();

        return back()->with('success', 'Branches bulk deleted.');
    }

    private function validateBranch(Request $request, ?string $ignoreId = null): array
    {
        return $request->validate([
            'code' => ['nullable', 'string', 'max:50'],
            'name' => ['required', 'string', 'max:255'],
            'email' => ['nullable', 'email', 'max:255'],
            'phone' => ['nullable', 'string', 'max:50'],
            'address' => ['nullable', 'string', 'max:255'],
            'country' => ['nullable', 'string', 'max:100'],
            'city' => ['nullable', 'string', 'max:100'],
            'timezone' => ['nullable', 'string', 'max:100'],
            'currency_id' => ['nullable', 'integer', 'exists:currencies,id'],
            'is_head_office' => ['boolean'],
            'active' => ['boolean'],
        ]);
    }
}
