<?php
namespace App\Console\Commands;

use App\Models\Branch;
use App\Models\User;
use Illuminate\Console\Command;

class AssignUsersToFirstBranch extends Command
{
    protected $signature = 'users:assign-first-branch {--dry-run : Show what would change without saving}';
    protected $description = 'Assign users without a branch_id to the first available branch';

    public function handle(): int
    {
        $branch = Branch::query()->orderBy('created_at')->first();

        if (!$branch) {
            $this->error('No branches found. Create a branch first.');
            return self::FAILURE;
        }

        $query = User::query()->whereNull('branch_id');
        $count = (clone $query)->count();

        if ($count === 0) {
            $this->info('No users without branch_id found.');
            return self::SUCCESS;
        }

        if ($this->option('dry-run')) {
            $this->info("DRY RUN: Would assign {$count} users to branch {$branch->id} ({$branch->name}).");
            return self::SUCCESS;
        }

        $updated = $query->update(['branch_id' => $branch->id]);

        $this->info("Assigned {$updated} users to branch {$branch->id} ({$branch->name}).");
        return self::SUCCESS;
    }
}
