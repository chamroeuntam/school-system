<?php

namespace App\Policies;

use App\Models\SheetSource;
use App\Models\User;

class SheetSourcePolicy
{
    /**
     * Only the creator can view/update/delete their sheet sources
     */
    public function update(User $user, SheetSource $sheetSource): bool
    {
        return $user->id === $sheetSource->created_by;
    }

    public function delete(User $user, SheetSource $sheetSource): bool
    {
        return $user->id === $sheetSource->created_by;
    }
}
