<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\AccountType;
use App\Models\COA;
use App\Services\Accounting\CoaSeeder;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ChartOfAccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        if ($branchId !== null) {
            CoaSeeder::seedForBranch($branchId, $user->id);
        }

        $search = trim((string) $request->input('search', ''));
        $perPage = (int) $request->input('per_page', 20);
        $page = (int) $request->input('page', 1);

        $sort = (string) $request->input('sort', 'id');
        $dir = strtolower((string) $request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $origin = (string) $request->input('origin', '');

        $sortable = ['id', 'name', 'code', 'active', 'created_at', 'updated_at'];
        if (!in_array($sort, $sortable, true)) {
            $sort = 'id';
        }

        $base = COA::query()
            ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
            ->when($origin === 'system', fn($q) => $q->where('is_system_generated', 1))
            ->when($origin === 'user', fn($q) => $q->where('is_system_generated', 0));

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $items = (clone $base)
            ->where('active', 1)
            ->orderBy($sort, $dir)
            ->paginate($perPage, ['*'], 'page', $page)
            ->withQueryString();

        $inactiveItems = null;
        $inactiveDrawer = (int) $request->input('inactive_drawer', 0) === 1;

        if ($inactiveDrawer) {
            $inactiveSearch = trim((string) $request->input('inactive_search', ''));
            $inactivePage = (int) $request->input('inactive_page', 1);
            $inactivePerPage = (int) $request->input('inactive_per_page', 10);

            $inactiveQ = COA::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->when($origin === 'system', fn($q) => $q->where('is_system_generated', 1))
                ->when($origin === 'user', fn($q) => $q->where('is_system_generated', 0))
                ->where('active', 0);

            if ($inactiveSearch !== '') {
                $inactiveQ->where(function ($q) use ($inactiveSearch) {
                    $q->where('name', 'like', "%{$inactiveSearch}%")
                        ->orWhere('code', 'like', "%{$inactiveSearch}%")
                        ->orWhere('description', 'like', "%{$inactiveSearch}%");
                });
            }

            $inactiveItems = $inactiveQ
                ->orderBy($sort, $dir)
                ->paginate($inactivePerPage, ['*'], 'inactive_page', $inactivePage)
                ->withQueryString();
        }

        return Inertia::render('Accounting/ChartOfAccounts/Index', [
            'items' => $items,
            'inactiveItems' => $inactiveItems,
            'query' => $request->all(),
            'accountTypes' => AccountType::query()
                ->where('active', 1)
                ->orderBy('name')
                ->get(['id', 'name']),
            'chartOfAccounts' => COA::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->where('active', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'code', 'c_o_a_id']),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $data = $request->validate([
            'name' => ['required', 'string', 'max:200'],
            'code' => ['required', 'string', 'max:60'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'string', 'max:255'],
            'account_type_id' => ['required', 'integer'],
            'active' => ['nullable', 'boolean'],
            'c_o_a_id' => ['nullable', 'integer'],
        ]);

        $data['active'] = array_key_exists('active', $data) ? (bool) $data['active'] : true;
        $data['branch_id'] = $branchId;
        $data['user_add_id'] = $user->id;
        $data['c_o_a_id'] = $data['c_o_a_id'] ?? 0;

        COA::create($data);

        return back();
    }

    public function update(Request $request, COA $chartOfAccount)
    {
        $this->assertBranchScope($request, $chartOfAccount);

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:200'],
            'code' => ['nullable', 'string', 'max:60'],
            'description' => ['nullable', 'string'],
            'parent_id' => ['nullable', 'string', 'max:255'],
            'account_type_id' => ['nullable', 'integer'],
            'active' => ['nullable', 'boolean'],
            'c_o_a_id' => ['nullable', 'integer'],
        ]);

        if (array_key_exists('active', $data)) {
            $data['active'] = (bool) $data['active'];
        }

        $chartOfAccount->update($data);

        return back();
    }

    public function destroy(Request $request, COA $chartOfAccount)
    {
        $this->assertBranchScope($request, $chartOfAccount);

        $chartOfAccount->delete();

        return back();
    }

    public function bulk(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $op = (string) $request->input('op', '');

        if ($op === 'inactivate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            COA::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->whereIn('id', $payload['ids'])
                ->update(['active' => 0]);

            return back();
        }

        if ($op === 'activate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            COA::query()
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

            $allowed = ['active', 'description'];
            $actions = array_intersect_key($payload['actions'], array_flip($allowed));

            COA::query()
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
                    $name = trim((string) ($row['name'] ?? $row['Name'] ?? ''));
                    $code = trim((string) ($row['code'] ?? $row['Code'] ?? ''));
                    if ($name === '' || $code === '') {
                        continue;
                    }

                    COA::create([
                        'branch_id' => $branchId,
                        'name' => $name,
                        'code' => $code,
                        'description' => $row['description'] ?? null,
                        'parent_id' => $row['parent_id'] ?? null,
                        'account_type_id' => (int) ($row['account_type_id'] ?? 0),
                        'active' => isset($row['active']) ? (bool) $row['active'] : true,
                        'user_add_id' => $user->id,
                        'c_o_a_id' => (int) ($row['c_o_a_id'] ?? 0),
                    ]);
                }
            });

            return back();
        }

        abort(400, 'Unknown bulk op');
    }

    private function assertBranchScope(Request $request, COA $record): void
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        if ($branchId !== null && (int) $record->branch_id !== (int) $branchId) {
            abort(403, 'Forbidden (branch scope)');
        }
    }
}
