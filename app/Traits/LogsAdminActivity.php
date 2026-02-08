<?php

namespace App\Traits;

use App\Models\AdminActivity;
use Illuminate\Support\Str;

trait LogsAdminActivity
{
    protected function logActivity($action, $targetType, $targetId, $targetUser, $status = 'completed')
    {
        AdminActivity::create([
            'activity_id' => $this->generateActivityId(),
            'admin_user_id' => auth()->id(),
            'action' => $action,
            'target_type' => $targetType,
            'target_id' => $targetId,
            'target_user' => $targetUser,
            'status' => $status,
        ]);
    }

    private function generateActivityId()
    {
        do {
            $id = 'A-' . str_pad(mt_rand(100, 999), 3, '0', STR_PAD_LEFT);
        } while (AdminActivity::where('activity_id', $id)->exists());

        return $id;
    }
}
