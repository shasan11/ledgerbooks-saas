<?php
namespace App\Support;

use Illuminate\Support\Facades\Auth;

class BranchContext
{
    public static function id(): ?string
    {
        return Auth::user()?->branch_id;
    }

    public static function requireId(): string
    {
        $id = self::id();
        abort_if(!$id, 403, 'User has no branch assigned.');
        return $id;
    }
}
