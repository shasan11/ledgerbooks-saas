<?php

namespace App\Services\Accounting;

use App\Models\AccountType;
use App\Models\COA;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CoaSeeder
{
    private const DEFAULT_COA = [
        // ASSETS (1000-1999)
        ['code' => '1000', 'name' => 'Assets', 'type' => 'asset', 'parent' => null, 'description' => null],
        ['code' => '1100', 'name' => 'Current Assets', 'type' => 'asset', 'parent' => '1000', 'description' => null],
        ['code' => '1110', 'name' => 'Cash on Hand', 'type' => 'asset', 'parent' => '1100', 'description' => null],
        ['code' => '1120', 'name' => 'Bank Accounts', 'type' => 'asset', 'parent' => '1100', 'description' => null],
        ['code' => '1130', 'name' => 'Accounts Receivable', 'type' => 'asset', 'parent' => '1100', 'description' => null],
        ['code' => '1140', 'name' => 'Inventory', 'type' => 'asset', 'parent' => '1100', 'description' => null],
        [
            'code' => '1150',
            'name' => 'Advances & Prepayments',
            'type' => 'asset',
            'parent' => '1100',
            'description' => null,
        ],
        ['code' => '1200', 'name' => 'Non-Current Assets', 'type' => 'asset', 'parent' => '1000', 'description' => null],
        [
            'code' => '1210',
            'name' => 'Property, Plant & Equipment',
            'type' => 'asset',
            'parent' => '1200',
            'description' => null,
        ],
        [
            'code' => '1220',
            'name' => 'Accumulated Depreciation',
            'type' => 'asset',
            'parent' => '1200',
            'description' => 'Contra-asset',
        ],

        // LIABILITIES (2000-2999)
        ['code' => '2000', 'name' => 'Liabilities', 'type' => 'liability', 'parent' => null, 'description' => null],
        [
            'code' => '2100',
            'name' => 'Current Liabilities',
            'type' => 'liability',
            'parent' => '2000',
            'description' => null,
        ],
        [
            'code' => '2110',
            'name' => 'Accounts Payable',
            'type' => 'liability',
            'parent' => '2100',
            'description' => null,
        ],
        ['code' => '2120', 'name' => 'Taxes Payable', 'type' => 'liability', 'parent' => '2100', 'description' => null],
        [
            'code' => '2130',
            'name' => 'Salaries Payable',
            'type' => 'liability',
            'parent' => '2100',
            'description' => null,
        ],
        [
            'code' => '2200',
            'name' => 'Non-Current Liabilities',
            'type' => 'liability',
            'parent' => '2000',
            'description' => null,
        ],
        ['code' => '2210', 'name' => 'Loans Payable', 'type' => 'liability', 'parent' => '2200', 'description' => null],

        // EQUITY (3000-3999)
        ['code' => '3000', 'name' => 'Equity', 'type' => 'equity', 'parent' => null, 'description' => null],
        ['code' => '3100', 'name' => "Owner's Capital", 'type' => 'equity', 'parent' => '3000', 'description' => null],
        [
            'code' => '3200',
            'name' => 'Retained Earnings',
            'type' => 'equity',
            'parent' => '3000',
            'description' => null,
        ],
        [
            'code' => '3300',
            'name' => 'Current Year Profit/Loss',
            'type' => 'equity',
            'parent' => '3000',
            'description' => null,
        ],

        // INCOME (4000-4999)
        ['code' => '4000', 'name' => 'Income', 'type' => 'income', 'parent' => null, 'description' => null],
        [
            'code' => '4100',
            'name' => 'Sales / Service Revenue',
            'type' => 'income',
            'parent' => '4000',
            'description' => null,
        ],
        ['code' => '4200', 'name' => 'Other Income', 'type' => 'income', 'parent' => '4000', 'description' => null],

        // DIRECT COSTS / COGS (commonly kept under expense in many SMEs)
        [
            'code' => '5000',
            'name' => 'Cost of Sales',
            'type' => 'expense',
            'parent' => null,
            'description' => 'Often treated as Expense in SME ledgers',
        ],
        [
            'code' => '5100',
            'name' => 'Purchases / Direct Costs',
            'type' => 'expense',
            'parent' => '5000',
            'description' => null,
        ],
        [
            'code' => '5200',
            'name' => 'Freight / Direct Expenses',
            'type' => 'expense',
            'parent' => '5000',
            'description' => null,
        ],

        // EXPENSES (6000-6999)
        [
            'code' => '6000',
            'name' => 'Operating Expenses',
            'type' => 'expense',
            'parent' => null,
            'description' => null,
        ],
        ['code' => '6100', 'name' => 'Rent Expense', 'type' => 'expense', 'parent' => '6000', 'description' => null],
        ['code' => '6200', 'name' => 'Utilities Expense', 'type' => 'expense', 'parent' => '6000', 'description' => null],
        [
            'code' => '6300',
            'name' => 'Salaries & Wages',
            'type' => 'expense',
            'parent' => '6000',
            'description' => null,
        ],
        ['code' => '6400', 'name' => 'Marketing Expense', 'type' => 'expense', 'parent' => '6000', 'description' => null],
        ['code' => '6500', 'name' => 'Office Expense', 'type' => 'expense', 'parent' => '6000', 'description' => null],
        [
            'code' => '6600',
            'name' => 'Depreciation Expense',
            'type' => 'expense',
            'parent' => '6000',
            'description' => null,
        ],
    ];

    public static function seedForBranch(?int $branchId, ?string $userId = null, bool $force = false): array
    {
        if (!$branchId) {
            return ['created' => 0, 'skipped' => true, 'reason' => 'branch_id is required'];
        }

        $existingQuery = COA::query()->where('branch_id', $branchId);
        if ($existingQuery->exists() && !$force) {
            return ['created' => 0, 'skipped' => true, 'reason' => 'COA already exists for this branch.'];
        }

        $existingByCode = $existingQuery->get(['id', 'code'])->keyBy('code');
        $codeToId = $existingByCode->map->id->all();

        $accountTypes = AccountType::query()->where('active', 1)->get(['id', 'name', 'category']);
        if ($accountTypes->isEmpty()) {
            return ['created' => 0, 'skipped' => true, 'reason' => 'No active account types available.'];
        }

        $typeMap = [];
        foreach ($accountTypes as $type) {
            if ($type->category) {
                $typeMap[strtolower((string) $type->category)] = $type->id;
            }
            if ($type->name) {
                $typeMap[strtolower((string) $type->name)] = $type->id;
            }
        }
        $fallbackTypeId = $accountTypes->first()->id;

        $pending = self::DEFAULT_COA;
        $createdCount = 0;

        DB::transaction(function () use (&$pending, &$createdCount, &$codeToId, $branchId, $userId, $typeMap, $fallbackTypeId) {
            COA::query()->where('branch_id', $branchId)->lockForUpdate()->get();

            $safety = 0;
            while (!empty($pending)) {
                $safety++;
                if ($safety > 80) {
                    throw ValidationException::withMessages([
                        'coa' => 'COA seeding failed (circular parent references or missing parents).',
                    ]);
                }

                $nextPending = [];
                $progressed = false;

                foreach ($pending as $row) {
                    if (array_key_exists($row['code'], $codeToId)) {
                        continue;
                    }

                    $parentId = null;
                    if (!empty($row['parent'])) {
                        if (!array_key_exists($row['parent'], $codeToId)) {
                            $nextPending[] = $row;
                            continue;
                        }
                        $parentId = $codeToId[$row['parent']];
                    }

                    $accountTypeId = $typeMap[$row['type']] ?? $fallbackTypeId;

                    $record = COA::create([
                        'branch_id' => $branchId,
                        'user_add_id' => $userId,
                        'active' => true,
                        'is_system_generated' => true,
                        'is_group' => false,
                        'is_system' => false,
                        'code' => $row['code'],
                        'name' => $row['name'],
                        'description' => $row['description'],
                        'parent_id' => $parentId,
                        'account_type_id' => $accountTypeId,
                        'c_o_a_id' => 0,
                    ]);

                    $codeToId[$row['code']] = $record->id;
                    $createdCount++;
                    $progressed = true;
                }

                if (!$progressed && !empty($nextPending)) {
                    $missingParents = array_values(
                        array_unique(
                            array_filter(
                                array_map(
                                    static fn ($row) => $row['parent'] ?? null,
                                    $nextPending
                                )
                            )
                        )
                    );
                    throw ValidationException::withMessages([
                        'coa' => 'COA seeding stuck. Missing parent codes: ' . implode(', ', $missingParents),
                    ]);
                }

                $pending = $nextPending;
            }
        });

        return [
            'created' => $createdCount,
            'skipped' => false,
            'branch_id' => $branchId,
        ];
    }
}
