<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\DealActivity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DealActivityController extends Controller
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

        $sortable = ['id', 'subject', 'type', 'status', 'active', 'created_at', 'updated_at'];
        if (!in_array($sort, $sortable, true)) {
            $sort = 'id';
        }

        $base = DealActivity::query()
            ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId));

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('subject', 'like', "%{$search}%")
                    ->orWhere('description', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('status', 'like', "%{$search}%");
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

            $inactiveQ = DealActivity::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->where('active', 0);

            if ($inactiveSearch !== '') {
                $inactiveQ->where(function ($q) use ($inactiveSearch) {
                    $q->where('subject', 'like', "%{$inactiveSearch}%")
                        ->orWhere('description', 'like', "%{$inactiveSearch}%")
                        ->orWhere('type', 'like', "%{$inactiveSearch}%")
                        ->orWhere('status', 'like', "%{$inactiveSearch}%");
                });
            }

            $inactiveItems = $inactiveQ
                ->orderBy($sort, $dir)
                ->paginate($inactivePerPage, ['*'], 'inactive_page', $inactivePage)
                ->withQueryString();
        }

        return Inertia::render('CRM/DealActivities/Index', [
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
            'type' => ['required', Rule::in(['call', 'meeting', 'task', 'email', 'note'])],
            'subject' => ['required', 'string', 'max:200'],
            'contact_id' => ['nullable', 'integer'],
            'deal_id' => ['nullable', 'integer'],
            'due_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['pending', 'done', 'cancelled'])],
            'assigned_to_id' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
        ]);

        $data['active'] = array_key_exists('active', $data) ? (bool) $data['active'] : true;
        $data['branch_id'] = $branchId;
        $data['user_add_id'] = $user->id;

        DealActivity::create($data);

        return back();
    }

    public function update(Request $request, DealActivity $dealActivity)
    {
        $this->assertBranchScope($request, $dealActivity);

        $data = $request->validate([
            'type' => ['sometimes', 'required', Rule::in(['call', 'meeting', 'task', 'email', 'note'])],
            'subject' => ['sometimes', 'required', 'string', 'max:200'],
            'contact_id' => ['nullable', 'integer'],
            'deal_id' => ['nullable', 'integer'],
            'due_at' => ['nullable', 'date'],
            'completed_at' => ['nullable', 'date'],
            'status' => ['nullable', Rule::in(['pending', 'done', 'cancelled'])],
            'assigned_to_id' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
        ]);

        if (array_key_exists('active', $data)) {
            $data['active'] = (bool) $data['active'];
        }

        $dealActivity->update($data);

        return back();
    }

    public function destroy(Request $request, DealActivity $dealActivity)
    {
        $this->assertBranchScope($request, $dealActivity);

        $dealActivity->delete();

        return back();
    }

    public function bulk(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $op = (string) $request->input('op', '');

        if ($op === 'inactivate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            DealActivity::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->whereIn('id', $payload['ids'])
                ->update(['active' => 0]);

            return back();
        }

        if ($op === 'activate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            DealActivity::query()
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

            $allowed = ['active', 'status'];
            $actions = array_intersect_key($payload['actions'], array_flip($allowed));

            DealActivity::query()
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
                    $subject = trim((string) ($row['subject'] ?? $row['Subject'] ?? ''));
                    $type = $row['type'] ?? $row['Type'] ?? null;
                    if ($subject === '' || !$type) {
                        continue;
                    }

                    if (!in_array($type, ['call', 'meeting', 'task', 'email', 'note'], true)) {
                        $type = 'task';
                    }

                    $status = $row['status'] ?? $row['Status'] ?? 'pending';
                    if (!in_array($status, ['pending', 'done', 'cancelled'], true)) {
                        $status = 'pending';
                    }

                    DealActivity::create([
                        'branch_id' => $branchId,
                        'type' => $type,
                        'subject' => $subject,
                        'contact_id' => $row['contact_id'] ?? null,
                        'deal_id' => $row['deal_id'] ?? null,
                        'due_at' => $row['due_at'] ?? null,
                        'completed_at' => $row['completed_at'] ?? null,
                        'status' => $status,
                        'assigned_to_id' => $row['assigned_to_id'] ?? null,
                        'description' => $row['description'] ?? null,
                        'active' => isset($row['active']) ? (bool) $row['active'] : true,
                        'user_add_id' => $user->id,
                    ]);
                }
            });

            return back();
        }

        abort(400, 'Unknown bulk op');
    }

    private function assertBranchScope(Request $request, DealActivity $record): void
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        if ($branchId !== null && (string) $record->branch_id !== (string) $branchId) {
            abort(403, 'Forbidden (branch scope)');
        }
    }
}
