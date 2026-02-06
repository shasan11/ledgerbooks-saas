<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\ContactGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class ContactController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $type = $request->input('type');
        $allowedTypes = ['customer', 'supplier', 'lead'];
        if (!in_array($type, $allowedTypes, true)) {
            $type = null;
        }

        $search = trim((string) $request->input('search', ''));
        $perPage = (int) $request->input('per_page', 20);
        $page = (int) $request->input('page', 1);

        $sort = (string) $request->input('sort', 'id');
        $dir = strtolower((string) $request->input('dir', 'desc')) === 'asc' ? 'asc' : 'desc';

        $sortable = ['id', 'name', 'type', 'active', 'created_at', 'updated_at'];
        if (!in_array($sort, $sortable, true)) {
            $sort = 'id';
        }

        $base = Contact::query()
            ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
            ->when($type !== null, fn($q) => $q->where('type', $type));

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('email', 'like', "%{$search}%")
                    ->orWhere('phone', 'like', "%{$search}%")
                    ->orWhere('pan', 'like', "%{$search}%");
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

            $inactiveQ = Contact::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->when($type !== null, fn($q) => $q->where('type', $type))
                ->where('active', 0);

            if ($inactiveSearch !== '') {
                $inactiveQ->where(function ($q) use ($inactiveSearch) {
                    $q->where('name', 'like', "%{$inactiveSearch}%")
                        ->orWhere('code', 'like', "%{$inactiveSearch}%")
                        ->orWhere('email', 'like', "%{$inactiveSearch}%")
                        ->orWhere('phone', 'like', "%{$inactiveSearch}%")
                        ->orWhere('pan', 'like', "%{$inactiveSearch}%");
                });
            }

            $inactiveItems = $inactiveQ
                ->orderBy($sort, $dir)
                ->paginate($inactivePerPage, ['*'], 'inactive_page', $inactivePage)
                ->withQueryString();
        }

        $title = match ($type) {
            'customer' => 'Customers',
            'supplier' => 'Suppliers',
            'lead' => 'Leads',
            default => 'Contacts',
        };

        return Inertia::render('CRM/Contacts/Index', [
            'items' => $items,
            'inactiveItems' => $inactiveItems,
            'query' => $request->all(),
            'type' => $type,
            'title' => $title,
            'contactGroups' => ContactGroup::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->where('active', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'contact_group_id']),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $data = $request->validate([
            'type' => ['required', Rule::in(['customer', 'supplier', 'lead'])],
            'name' => ['required', 'string', 'max:200'],
            'code' => ['nullable', 'string', 'max:50'],
            'pan' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:60'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string'],
            'group_id' => ['nullable', 'string', 'max:255'],
            'accept_purchase' => ['nullable', 'boolean'],
            'credit_terms_days' => ['nullable', 'integer'],
            'credit_limit' => ['nullable', 'numeric'],
            'receivable_account_id' => ['nullable', 'string', 'max:255'],
            'payable_account_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
            'contact_group_id' => ['nullable', 'integer'],
        ]);

        $data['active'] = array_key_exists('active', $data) ? (bool) $data['active'] : true;
        $data['accept_purchase'] = array_key_exists('accept_purchase', $data) ? (bool) $data['accept_purchase'] : false;
        $data['branch_id'] = $branchId;
        $data['user_add_id'] = $user->id;
        $data['contact_group_id'] = $data['contact_group_id'] ?? 0;

        Contact::create($data);

        return back();
    }

    public function update(Request $request, Contact $contact)
    {
        $this->assertBranchScope($request, $contact);

        $data = $request->validate([
            'type' => ['sometimes', 'required', Rule::in(['customer', 'supplier', 'lead'])],
            'name' => ['sometimes', 'required', 'string', 'max:200'],
            'code' => ['nullable', 'string', 'max:50'],
            'pan' => ['nullable', 'string', 'max:50'],
            'phone' => ['nullable', 'string', 'max:60'],
            'email' => ['nullable', 'email', 'max:150'],
            'address' => ['nullable', 'string'],
            'group_id' => ['nullable', 'string', 'max:255'],
            'accept_purchase' => ['nullable', 'boolean'],
            'credit_terms_days' => ['nullable', 'integer'],
            'credit_limit' => ['nullable', 'numeric'],
            'receivable_account_id' => ['nullable', 'string', 'max:255'],
            'payable_account_id' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
            'contact_group_id' => ['nullable', 'integer'],
        ]);

        if (array_key_exists('active', $data)) {
            $data['active'] = (bool) $data['active'];
        }

        if (array_key_exists('accept_purchase', $data)) {
            $data['accept_purchase'] = (bool) $data['accept_purchase'];
        }

        $contact->update($data);

        return back();
    }

    public function destroy(Request $request, Contact $contact)
    {
        $this->assertBranchScope($request, $contact);

        $contact->delete();

        return back();
    }

    public function bulk(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $op = (string) $request->input('op', '');

        if ($op === 'inactivate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            Contact::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->whereIn('id', $payload['ids'])
                ->update(['active' => 0]);

            return back();
        }

        if ($op === 'activate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            Contact::query()
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

            $allowed = ['active', 'notes'];
            $actions = array_intersect_key($payload['actions'], array_flip($allowed));

            Contact::query()
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
                    if ($name === '') {
                        continue;
                    }

                    $type = (string) ($row['type'] ?? $row['Type'] ?? 'customer');
                    if (!in_array($type, ['customer', 'supplier', 'lead'], true)) {
                        $type = 'customer';
                    }

                    Contact::create([
                        'branch_id' => $branchId,
                        'type' => $type,
                        'name' => $name,
                        'code' => $row['code'] ?? null,
                        'pan' => $row['pan'] ?? null,
                        'phone' => $row['phone'] ?? null,
                        'email' => $row['email'] ?? null,
                        'address' => $row['address'] ?? null,
                        'group_id' => $row['group_id'] ?? null,
                        'accept_purchase' => isset($row['accept_purchase']) ? (bool) $row['accept_purchase'] : false,
                        'credit_terms_days' => (int) ($row['credit_terms_days'] ?? 0),
                        'credit_limit' => (float) ($row['credit_limit'] ?? 0),
                        'receivable_account_id' => $row['receivable_account_id'] ?? null,
                        'payable_account_id' => $row['payable_account_id'] ?? null,
                        'notes' => $row['notes'] ?? null,
                        'active' => isset($row['active']) ? (bool) $row['active'] : true,
                        'user_add_id' => $user->id,
                        'contact_group_id' => (int) ($row['contact_group_id'] ?? 0),
                    ]);
                }
            });

            return back();
        }

        abort(400, 'Unknown bulk op');
    }

    private function assertBranchScope(Request $request, Contact $record): void
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        if ($branchId !== null && (int) $record->branch_id !== (int) $branchId) {
            abort(403, 'Forbidden (branch scope)');
        }
    }
}
