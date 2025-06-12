<?php

namespace App\Helpers;

use App\Models\ActivityLog;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Request;

class ActivityLogger
{
    public static function log($action, $description = null)
    {
        $user = Auth::user();

        ActivityLog::create([
            'user_id'    => $user?->id,
            'name'       => $user?->name ?? 'System',
            'role'       => $user?->getRoleNames()->first() ?? 'system',
            'action'     => $action,
            'description'=> $description,
            'ip_address' => Request::ip(),
        ]);
    }
}
