<?php

namespace App\Http\Controllers\CRM;

use App\Http\Controllers\Controller;
use App\Models\Contact;
use App\Models\Currency;
use App\Models\Deal;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class DealController extends Controller
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

        $sortable = ['id', 'title', 'stage', 'active', 'created_at', 'updated_at'];
        if (!in_array($sort, $sortable, true)) {
            $sort = 'id';
        }

        $base = Deal::query()
            ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId));

        if ($search !== '') {
            $base->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('code', 'like', "%{$search}%")
                    ->orWhere('source', 'like', "%{$search}%")
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

            $inactiveQ = Deal::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->where('active', 0);

            if ($inactiveSearch !== '') {
                $inactiveQ->where(function ($q) use ($inactiveSearch) {
                    $q->where('title', 'like', "%{$inactiveSearch}%")
                        ->orWhere('code', 'like', "%{$inactiveSearch}%")
                        ->orWhere('source', 'like', "%{$inactiveSearch}%")
                        ->orWhere('description', 'like', "%{$inactiveSearch}%");
                });
            }

            $inactiveItems = $inactiveQ
                ->orderBy($sort, $dir)
                ->paginate($inactivePerPage, ['*'], 'inactive_page', $inactivePage)
                ->withQueryString();
        }

        return Inertia::render('CRM/Deals/Index', [
            'items' => $items,
            'inactiveItems' => $inactiveItems,
            'query' => $request->all(),
            'contacts' => Contact::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->where('active', 1)
                ->orderBy('name')
                ->get(['id', 'name', 'code']),
            'currencies' => Currency::query()
                ->where('active', 1)
                ->orderBy('code')
                ->get(['id', 'code', 'name']),
            'users' => User::query()
                ->orderBy('name')
                ->get(['id', 'name', 'email']),
        ]);
    }

    public function store(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $data = $request->validate([
            'code' => ['nullable', 'string', 'max:50'],
            'title' => ['required', 'string', 'max:200'],
            'contact_id' => ['required', 'integer'],
            'stage' => ['nullable', Rule::in(['lead', 'qualified', 'proposal', 'won', 'lost'])],
            'expected_close' => ['nullable', 'date'],
            'probability' => ['nullable', 'integer', 'min:0', 'max:100'],
            'currency_id' => ['nullable', 'integer'],
            'expected_value' => ['nullable', 'numeric'],
            'source' => ['nullable', 'string', 'max:80'],
            'owner_id' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
        ]);

        $data['active'] = array_key_exists('active', $data) ? (bool) $data['active'] : true;
        $data['branch_id'] = $branchId;
        $data['user_add_id'] = $user->id;

        Deal::create($data);

        return back();
    }

    public function update(Request $request, Deal $deal)
    {
        $this->assertBranchScope($request, $deal);

        $data = $request->validate([
            'code' => ['nullable', 'string', 'max:50'],
            'title' => ['sometimes', 'required', 'string', 'max:200'],
            'contact_id' => ['nullable', 'integer'],
            'stage' => ['nullable', Rule::in(['lead', 'qualified', 'proposal', 'won', 'lost'])],
            'expected_close' => ['nullable', 'date'],
            'probability' => ['nullable', 'integer', 'min:0', 'max:100'],
            'currency_id' => ['nullable', 'integer'],
            'expected_value' => ['nullable', 'numeric'],
            'source' => ['nullable', 'string', 'max:80'],
            'owner_id' => ['nullable', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'active' => ['nullable', 'boolean'],
        ]);

        if (array_key_exists('active', $data)) {
            $data['active'] = (bool) $data['active'];
        }

        $deal->update($data);

        return back();
    }

    public function destroy(Request $request, Deal $deal)
    {
        $this->assertBranchScope($request, $deal);

        $deal->delete();

        return back();
    }

    public function bulk(Request $request)
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        $op = (string) $request->input('op', '');

        if ($op === 'inactivate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            Deal::query()
                ->when($branchId !== null, fn($q) => $q->where('branch_id', $branchId))
                ->whereIn('id', $payload['ids'])
                ->update(['active' => 0]);

            return back();
        }

        if ($op === 'activate') {
            $payload = $request->validate(['ids' => ['required', 'array'], 'ids.*' => ['integer']]);
            Deal::query()
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

            $allowed = ['active', 'stage'];
            $actions = array_intersect_key($payload['actions'], array_flip($allowed));

            Deal::query()
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
                    $title = trim((string) ($row['title'] ?? $row['Title'] ?? ''));
                    $contactId = $row['contact_id'] ?? $row['Contact ID'] ?? null;
                    if ($title === '' || !$contactId) {
                        continue;
                    }

                    $stage = $row['stage'] ?? $row['Stage'] ?? 'lead';
                    if (!in_array($stage, ['lead', 'qualified', 'proposal', 'won', 'lost'], true)) {
                        $stage = 'lead';
                    }

                    Deal::create([
                        'branch_id' => $branchId,
                        'code' => $row['code'] ?? null,
                        'title' => $title,
                        'contact_id' => (int) $contactId,
                        'stage' => $stage,
                        'expected_close' => $row['expected_close'] ?? null,
                        'probability' => (int) ($row['probability'] ?? 0),
                        'currency_id' => $row['currency_id'] ?? null,
                        'expected_value' => (float) ($row['expected_value'] ?? 0),
                        'source' => $row['source'] ?? null,
                        'owner_id' => $row['owner_id'] ?? null,
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

    private function assertBranchScope(Request $request, Deal $record): void
    {
        $user = $request->user();
        $branchId = $user->branch_id ?? null;

        if ($branchId !== null && (int) $record->branch_id !== (int) $branchId) {
            abort(403, 'Forbidden (branch scope)');
        }
    }
}
