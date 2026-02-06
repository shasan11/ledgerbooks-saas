<?php

namespace App\Http\Controllers\Accounting;

use App\Http\Controllers\Controller;
use App\Models\BankAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class BankAccountController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $search = trim((string) $request->input('search', ''));
        $perPage = (int) $request->input('per_page', 20);
        $page = (int) $request->input('page', 1);

        $sort = (string) $request->input('sort', 'id');
        $dir = strtolower((string) $request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $sortable = ['id', 'display_name', 'type', 'active', 'created_at', 'updated_at'];
        if (!in_array($sort, $sortable, true)) {
            $sort = 'id';
        }

        $base = BankAccount::query()
            ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId));

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('display_name', 'like', "%{$search}%")
                    ->orWhere('bank_name', 'like', "%{$search}%")
                    ->orWhere('account_name', 'like', "%{$search}%")
                    ->orWhere('account_number', 'like', "%{$search}%")
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

            $inactiveQ = BankAccount::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->where('active', 0);

            if ($inactiveSearch !== '') {
                $inactiveQ->where(function ($q) use ($inactiveSearch) {
                    $q->where('display_name', 'like', "%{$inactiveSearch}%")
                        ->orWhere('bank_name', 'like', "%{$inactiveSearch}%")
                        ->orWhere('account_name', 'like', "%{$inactiveSearch}%")
                        ->orWhere('account_number', 'like', "%{$inactiveSearch}%")
                        ->orWhere('code', 'like', "%{$inactiveSearch}%")
                        ->orWhere('description', 'like', "%{$inactiveSearch}%");
                });
            }

            $inactiveItems = $inactiveQ
                ->orderBy($sort, $dir)
                ->paginate($inactivePerPage, ['*'], 'inactive_page', $inactivePage)
                ->withQueryString();
        }

        return Inertia::render('Accounting/BankAccounts/Index', [
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
            'type' => ['required', Rule::in(['bank', 'cash'])],
            'bank_name' => ['nullable', 'string', 'max:150'],
            'display_name' => ['required', 'string', 'max:150'],
            'code' => ['nullable', 'string', 'max:50'],
            'account_name' => ['nullable', 'string', 'max:150'],
            'account_number' => ['nullable', 'string', 'max:80'],
            'account_type' => ['nullable', Rule::in(['saving', 'current'])],
            'currency_id' => ['nullable', 'integer'],
            'coa_account_id' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
            'c_o_a_id' => ['nullable', 'integer'],
        ]);

        $data['active'] = array_key_exists('active', $data) ? (bool) $data['active'] : true;
        $data['branch_id'] = $branchId;
        $data['user_add_id'] = $user->id;
        $data['c_o_a_id'] = $data['c_o_a_id'] ?? 0;

        BankAccount::create($data);

        return back();
    }

    public function update(Request $request, BankAccount $bankAccount)
    {
        $this->assertBranchScope($request, $bankAccount);

        $data = $request->validate([
            'type' => ['nullable', Rule::in(['bank', 'cash'])],
            'bank_name' => ['nullable', 'string', 'max:150'],
            'display_name' => ['sometimes', 'required', 'string', 'max:150'],
            'code' => ['nullable', 'string', 'max:50'],
            'account_name' => ['nullable', 'string', 'max:150'],
            'account_number' => ['nullable', 'string', 'max:80'],
            'account_type' => ['nullable', Rule::in(['saving', 'current'])],
            'currency_id' => ['nullable', 'integer'],
            'coa_account_id' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
            'c_o_a_id' => ['nullable', 'integer'],
        ]);

        if (array_key_exists('active', $data)) {
            $data['active'] = (bool) $data['active'];
        }

        $bankAccount->update($data);

        return back();
    }

    public function destroy(Request $request, BankAccount $bankAccount)
    {
        $this->assertBranchScope($request, $bankAccount);

        $bankAccount->delete();

        return back();
    }

    public function bulk(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $op = (string) $request->input('op', '');

        if ($op === 'inactivate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            BankAccount::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->whereIn('id', $payload['ids'])
                ->update(['active' => 0]);

            return back();
        }

        if ($op === 'activate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            BankAccount::query()
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

            BankAccount::query()
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
                    $displayName = trim((string) ($row['display_name'] ?? $row['Display Name'] ?? ''));
                    if ($displayName === '') {
                        continue;
                    }

                    $type = $row['type'] ?? $row['Type'] ?? 'bank';
                    if (!in_array($type, ['bank', 'cash'], true)) {
                        $type = 'bank';
                    }

                    $accountType = $row['account_type'] ?? $row['Account Type'] ?? null;
                    if ($accountType && !in_array($accountType, ['saving', 'current'], true)) {
                        $accountType = null;
                    }

                    BankAccount::create([
                        'branch_id' => $branchId,
                        'type' => $type,
                        'bank_name' => $row['bank_name'] ?? null,
                        'display_name' => $displayName,
                        'code' => $row['code'] ?? null,
                        'account_name' => $row['account_name'] ?? null,
                        'account_number' => $row['account_number'] ?? null,
                        'account_type' => $accountType,
                        'currency_id' => $row['currency_id'] ?? null,
                        'coa_account_id' => $row['coa_account_id'] ?? null,
                        'description' => $row['description'] ?? null,
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

    private function assertBranchScope(Request $request, BankAccount $record): void
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        if ($branchId !== null && (int) $record->branch_id !== (int) $branchId) {
            abort(403, 'Forbidden (branch scope)');
        }
    }
}
