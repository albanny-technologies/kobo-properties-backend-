<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;

class NotificationController extends Controller
{
    // Get all notifications for the authenticated user
    public function index()
    {
        $notifications = auth()->user()->notifications;

        return response()->json([
            'status' => true,
            'notifications' => $notifications
        ]);
    }

    // Mark a single notification as read
    public function markAsRead($id)
    {
        $notification = auth()->user()->notifications()->find($id);

        if ($notification) {
            $notification->markAsRead();

            return response()->json([
                'status' => true,
                'message' => 'Notification marked as read'
            ]);
        }

        return response()->json([
            'status' => false,
            'message' => 'Notification not found'
        ], 404);
    }

    // Mark all notifications as read
    public function markAllAsRead()
    {
        auth()->user()->unreadNotifications->markAsRead();

        return response()->json([
            'status' => true,
            'message' => 'All notifications marked as read'
        ]);
    }
}

