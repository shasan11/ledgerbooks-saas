<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\ContactGroup;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class ContactGroupController extends Controller
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

        $sortable = ['id', 'name', 'active', 'created_at', 'updated_at'];
        if (!in_array($sort, $sortable, true)) {
            $sort = 'id';
        }

        $base = ContactGroup::query()
            ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId));

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
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

            $inactiveQ = ContactGroup::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->where('active', 0);

            if ($inactiveSearch !== '') {
                $inactiveQ->where(function ($q) use ($inactiveSearch) {
                    $q->where('name', 'like', "%{$inactiveSearch}%")
                        ->orWhere('description', 'like', "%{$inactiveSearch}%");
                });
            }

            $inactiveItems = $inactiveQ
                ->orderBy($sort, $dir)
                ->paginate($inactivePerPage, ['*'], 'inactive_page', $inactivePage)
                ->withQueryString();
        }

        return Inertia::render('CRM/ContactGroups/Index', [
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
            'name' => ['required', 'string', 'max:150'],
            'parent_id' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
            'contact_group_id' => ['nullable', 'integer'],
        ]);

        $data['active'] = array_key_exists('active', $data) ? (bool) $data['active'] : true;
        $data['branch_id'] = $branchId;
        $data['user_add_id'] = $user->id;
        $data['contact_group_id'] = $data['contact_group_id'] ?? 0;

        ContactGroup::create($data);

        return back();
    }

    public function update(Request $request, ContactGroup $contactGroup)
    {
        $this->assertBranchScope($request, $contactGroup);

        $data = $request->validate([
            'name' => ['sometimes', 'required', 'string', 'max:150'],
            'parent_id' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
            'contact_group_id' => ['nullable', 'integer'],
        ]);

        if (array_key_exists('active', $data)) {
            $data['active'] = (bool) $data['active'];
        }

        $contactGroup->update($data);

        return back();
    }

    public function destroy(Request $request, ContactGroup $contactGroup)
    {
        $this->assertBranchScope($request, $contactGroup);

        $contactGroup->delete();

        return back();
    }

    public function bulk(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $op = (string) $request->input('op', '');

        if ($op === 'inactivate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            ContactGroup::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->whereIn('id', $payload['ids'])
                ->update(['active' => 0]);

            return back();
        }

        if ($op === 'activate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            ContactGroup::query()
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

            ContactGroup::query()
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

                    ContactGroup::create([
                        'branch_id' => $branchId,
                        'name' => $name,
                        'parent_id' => $row['parent_id'] ?? null,
                        'description' => (string) ($row['description'] ?? $row['Description'] ?? ''),
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

    private function assertBranchScope(Request $request, ContactGroup $record): void
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        if ($branchId !== null && (int) $record->branch_id !== (int) $branchId) {
            abort(403, 'Forbidden (branch scope)');
        }
    }
}
