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
        $perPage = (int) $request->query('per_page', 15);
        $page = (int) $request->query('page', 1);

        $filters = [
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

        $items = (clone $query)
            ->where('active', 1)
            ->orderBy($sort, $dir)
            ->paginate($perPage, ['*'], 'page', $page)
            ->withQueryString();

        $inactiveItems = null;
        $inactiveDrawer = (int) $request->query('inactive_drawer', 0) === 1;

        if ($inactiveDrawer) {
            $inactiveSearch = trim((string) $request->query('inactive_search', ''));
            $inactivePage = (int) $request->query('inactive_page', 1);
            $inactivePerPage = (int) $request->query('inactive_per_page', 10);

            $inactiveQuery = (clone $query)->where('active', 0);

            if ($inactiveSearch !== '') {
                $inactiveQuery->where(function ($qq) use ($inactiveSearch) {
                    $qq->where('name', 'like', "%{$inactiveSearch}%")
                       ->orWhere('code', 'like', "%{$inactiveSearch}%")
                       ->orWhere('email', 'like', "%{$inactiveSearch}%")
                       ->orWhere('phone', 'like', "%{$inactiveSearch}%")
                       ->orWhere('country', 'like', "%{$inactiveSearch}%")
                       ->orWhere('city', 'like', "%{$inactiveSearch}%");
                });
            }

            $inactiveItems = $inactiveQuery
                ->orderBy($sort, $dir)
                ->paginate($inactivePerPage, ['*'], 'inactive_page', $inactivePage)
                ->withQueryString();
        }

        return Inertia::render('Branches/Index', [
            'items' => $items,
            'inactiveItems' => $inactiveItems,
            'query' => $request->all(),
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

    public function bulk(Request $request)
    {
        $op = (string) $request->input('op', '');

        if ($op === 'inactivate') {
            $payload = $request->validate([
                'ids' => ['required', 'array'],
                'ids.*' => ['required', 'uuid', 'exists:branches,id'],
            ]);

            Branch::whereIn('id', $payload['ids'])->update(['active' => 0]);

            return back()->with('success', 'Branches marked inactive.');
        }

        if ($op === 'activate') {
            $payload = $request->validate([
                'ids' => ['required', 'array'],
                'ids.*' => ['required', 'uuid', 'exists:branches,id'],
            ]);

            Branch::whereIn('id', $payload['ids'])->update(['active' => 1]);

            return back()->with('success', 'Branches marked active.');
        }

        if ($op === 'import') {
            $payload = $request->validate([
                'rows' => ['required', 'array'],
            ]);

            $rows = collect($payload['rows'])->map(function ($row) {
                $name = trim((string) ($row['name'] ?? $row['Name'] ?? ''));
                if ($name === '') {
                    return null;
                }

                return [
                    'code' => $row['code'] ?? $row['Code'] ?? null,
                    'name' => $name,
                    'email' => $row['email'] ?? $row['Email'] ?? null,
                    'phone' => $row['phone'] ?? $row['Phone'] ?? null,
                    'address' => $row['address'] ?? $row['Address'] ?? null,
                    'country' => $row['country'] ?? $row['Country'] ?? null,
                    'city' => $row['city'] ?? $row['City'] ?? null,
                    'timezone' => $row['timezone'] ?? $row['Timezone'] ?? null,
                    'currency_id' => $row['currency_id'] ?? $row['Currency ID'] ?? null,
                    'is_head_office' => (bool) ($row['is_head_office'] ?? $row['Head Office'] ?? false),
                    'active' => isset($row['active']) ? (bool) $row['active'] : true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->filter()->all();

            if (!empty($rows)) {
                Branch::insert($rows);
            }

            return back()->with('success', 'Branches imported.');
        }

        abort(400, 'Unknown bulk op');
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
