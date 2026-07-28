<?php

namespace App\Http\Controllers;

abstract class Controller
{
    protected function logActivity($action, $details = null, $subject = null)
    {
        \App\Models\ActivityLog::create([
            'user_email' => session('admin_email', session('admin_user_name')),
            'action' => $action,
            'entity_type' => $subject ? get_class($subject) : null,
            'entity_id' => $subject ? $subject->id : null,
            'details' => $details,
            'ip_address' => request()->ip(),
        ]);
    }
}
