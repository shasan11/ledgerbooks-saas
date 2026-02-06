<?php

namespace App\Http\Controllers\HRM;

use App\Http\Controllers\Controller;
use App\Models\Employee;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Inertia\Inertia;

class EmployeeController extends Controller
{
    public function index(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $sort = $request->query('sort', 'created_at');
        $dir = strtolower($request->query('dir', 'desc')) === 'asc' ? 'asc' : 'desc';
        $perPage = (int) $request->query('per_page', 15);
        $page = (int) $request->query('page', 1);

        $filters = [
            'department' => $request->query('department'),
            'designation' => $request->query('designation'),
            'employment_type' => $request->query('employment_type'),
            'status' => $request->query('status'),
        ];

        $allowedSorts = [
            'created_at',
            'employee_id',
            'first_name',
            'last_name',
            'department',
            'designation',
            'employment_type',
            'status',
            'active',
        ];

        $query = Employee::query();

        if ($q !== '') {
            $query->where(function ($qq) use ($q) {
                $qq->where('employee_id', 'like', "%{$q}%")
                    ->orWhere('first_name', 'like', "%{$q}%")
                    ->orWhere('last_name', 'like', "%{$q}%")
                    ->orWhere('email', 'like', "%{$q}%")
                    ->orWhere('phone', 'like', "%{$q}%")
                    ->orWhere('department', 'like', "%{$q}%")
                    ->orWhere('designation', 'like', "%{$q}%");
            });
        }

        if (!empty($filters['department'])) {
            $query->where('department', $filters['department']);
        }
        if (!empty($filters['designation'])) {
            $query->where('designation', $filters['designation']);
        }
        if (!empty($filters['employment_type'])) {
            $query->where('employment_type', $filters['employment_type']);
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
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
                    $qq->where('employee_id', 'like', "%{$inactiveSearch}%")
                        ->orWhere('first_name', 'like', "%{$inactiveSearch}%")
                        ->orWhere('last_name', 'like', "%{$inactiveSearch}%")
                        ->orWhere('email', 'like', "%{$inactiveSearch}%")
                        ->orWhere('phone', 'like', "%{$inactiveSearch}%")
                        ->orWhere('department', 'like', "%{$inactiveSearch}%")
                        ->orWhere('designation', 'like', "%{$inactiveSearch}%");
                });
            }

            $inactiveItems = $inactiveQuery
                ->orderBy($sort, $dir)
                ->paginate($inactivePerPage, ['*'], 'inactive_page', $inactivePage)
                ->withQueryString();
        }

        return Inertia::render('HRM/Employees/Index', [
            'items' => $items,
            'inactiveItems' => $inactiveItems,
            'query' => $request->all(),
            'meta' => [
                'departments' => Employee::query()
                    ->whereNotNull('department')
                    ->distinct()
                    ->orderBy('department')
                    ->pluck('department'),
                'designations' => Employee::query()
                    ->whereNotNull('designation')
                    ->distinct()
                    ->orderBy('designation')
                    ->pluck('designation'),
                'employmentTypes' => [
                    'Full-time',
                    'Part-time',
                    'Contract',
                    'Intern',
                ],
                'statuses' => [
                    'Active',
                    'On Leave',
                    'Probation',
                    'Terminated',
                ],
                'genders' => [
                    'Male',
                    'Female',
                    'Non-binary',
                    'Prefer not to say',
                ],
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data = $this->validateEmployee($request);
        $data['active'] = array_key_exists('active', $data) ? (bool) $data['active'] : true;

        Employee::create($data);

        return back()->with('success', 'Employee created.');
    }

    public function update(Request $request, Employee $employee)
    {
        $data = $this->validateEmployee($request, $employee->id);

        if (array_key_exists('active', $data)) {
            $data['active'] = (bool) $data['active'];
        }

        $employee->update($data);

        return back()->with('success', 'Employee updated.');
    }

    public function destroy(Employee $employee)
    {
        $employee->delete();
        return back()->with('success', 'Employee deleted.');
    }

    public function bulk(Request $request)
    {
        $op = (string) $request->input('op', '');

        if ($op === 'inactivate') {
            $payload = $request->validate([
                'ids' => ['required', 'array'],
                'ids.*' => ['required', 'uuid', 'exists:employees,id'],
            ]);

            Employee::whereIn('id', $payload['ids'])->update(['active' => 0]);

            return back()->with('success', 'Employees marked inactive.');
        }

        if ($op === 'activate') {
            $payload = $request->validate([
                'ids' => ['required', 'array'],
                'ids.*' => ['required', 'uuid', 'exists:employees,id'],
            ]);

            Employee::whereIn('id', $payload['ids'])->update(['active' => 1]);

            return back()->with('success', 'Employees marked active.');
        }

        if ($op === 'import') {
            $payload = $request->validate([
                'rows' => ['required', 'array'],
            ]);

            $rows = collect($payload['rows'])->map(function ($row) {
                $employeeId = trim((string) ($row['employee_id'] ?? $row['Employee ID'] ?? ''));
                $firstName = trim((string) ($row['first_name'] ?? $row['First Name'] ?? ''));
                $lastName = trim((string) ($row['last_name'] ?? $row['Last Name'] ?? ''));

                if ($employeeId === '' || $firstName === '' || $lastName === '') {
                    return null;
                }

                return [
                    'employee_id' => $employeeId,
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'email' => $row['email'] ?? $row['Email'] ?? null,
                    'phone' => $row['phone'] ?? $row['Phone'] ?? null,
                    'gender' => $row['gender'] ?? $row['Gender'] ?? null,
                    'date_of_birth' => $row['date_of_birth'] ?? $row['Date of Birth'] ?? null,
                    'hire_date' => $row['hire_date'] ?? $row['Hire Date'] ?? null,
                    'department' => $row['department'] ?? $row['Department'] ?? null,
                    'designation' => $row['designation'] ?? $row['Designation'] ?? null,
                    'employment_type' => $row['employment_type'] ?? $row['Employment Type'] ?? null,
                    'status' => $row['status'] ?? $row['Status'] ?? null,
                    'basic_salary' => $row['basic_salary'] ?? $row['Basic Salary'] ?? null,
                    'address' => $row['address'] ?? $row['Address'] ?? null,
                    'emergency_contact_name' => $row['emergency_contact_name'] ?? $row['Emergency Contact Name'] ?? null,
                    'emergency_contact_phone' => $row['emergency_contact_phone'] ?? $row['Emergency Contact Phone'] ?? null,
                    'bank_name' => $row['bank_name'] ?? $row['Bank Name'] ?? null,
                    'bank_account_number' => $row['bank_account_number'] ?? $row['Bank Account Number'] ?? null,
                    'tax_id' => $row['tax_id'] ?? $row['Tax ID'] ?? null,
                    'active' => isset($row['active']) ? (bool) $row['active'] : true,
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            })->filter()->all();

            if (!empty($rows)) {
                Employee::insert($rows);
            }

            return back()->with('success', 'Employees imported.');
        }

        abort(400, 'Unknown bulk op');
    }

    private function validateEmployee(Request $request, ?string $employeeId = null): array
    {
        return $request->validate([
            'employee_id' => [
                'required',
                'string',
                'max:40',
                Rule::unique('employees', 'employee_id')->ignore($employeeId, 'id'),
            ],
            'first_name' => ['required', 'string', 'max:100'],
            'last_name' => ['required', 'string', 'max:100'],
            'email' => ['nullable', 'email', 'max:150'],
            'phone' => ['nullable', 'string', 'max:50'],
            'gender' => ['nullable', 'string', 'max:20'],
            'date_of_birth' => ['nullable', 'date'],
            'hire_date' => ['nullable', 'date'],
            'department' => ['nullable', 'string', 'max:120'],
            'designation' => ['nullable', 'string', 'max:120'],
            'employment_type' => ['nullable', 'string', 'max:40'],
            'status' => ['nullable', 'string', 'max:40'],
            'basic_salary' => ['nullable', 'numeric', 'min:0'],
            'address' => ['nullable', 'string'],
            'emergency_contact_name' => ['nullable', 'string', 'max:120'],
            'emergency_contact_phone' => ['nullable', 'string', 'max:50'],
            'bank_name' => ['nullable', 'string', 'max:120'],
            'bank_account_number' => ['nullable', 'string', 'max:60'],
            'tax_id' => ['nullable', 'string', 'max:60'],
            'active' => ['nullable', 'boolean'],
        ]);
    }
}
