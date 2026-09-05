<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Notification;

class AdminNotificationController extends Controller
{
    public function markAllRead()
    {
        $userId = session('admin_user_id');

        Notification::forAdmins($userId)
            ->unread()
            ->update(['read_at' => now()]);

        return back()->with('success', 'All notifications marked as read.');
    }

    public function markRead(Notification $notification)
    {
        $userId = session('admin_user_id');
        $visible = $notification->user_id === null || $notification->user_id === $userId;

        if (!$visible) {
            abort(403);
        }

        $notification->markRead();

        return response()->json(['success' => true]);
    }
}