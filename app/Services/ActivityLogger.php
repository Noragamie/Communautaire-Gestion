<?php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log(string $action, string $targetType = null, int $targetId = null, string $targetLabel = null): void
    {
        $user = Auth::user();

        ActivityLog::create([
            'user_id'      => $user?->id,
            'role'         => $user?->role,
            'action'       => $action,
            'target_type'  => $targetType,
            'target_id'    => $targetId,
            'target_label' => $targetLabel,
            'ip_address'   => Request::ip(),
        ]);
    }
}
