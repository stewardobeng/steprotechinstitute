<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;

class NotificationController extends Controller
{
    /**
     * Get user's notifications
     */
    public function index(Request $request)
    {
        $user = auth()->user();
        
        $query = $user->notifications()->latest();
        
        // Filter by read status
        if ($request->has('read')) {
            $query->where('read', $request->boolean('read'));
        }
        
        // Get unread count
        $unreadCount = $user->unreadNotifications()->count();
        
        // Get notifications
        $notifications = $query->paginate(20);
        
        if ($request->expectsJson() || $request->ajax() || $request->wantsJson()) {
            return response()->json([
                'notifications' => [
                    'data' => $notifications->items(),
                    'current_page' => $notifications->currentPage(),
                    'last_page' => $notifications->lastPage(),
                    'per_page' => $notifications->perPage(),
                    'total' => $notifications->total(),
                ],
                'unread_count' => $unreadCount,
            ]);
        }
        
        return view('notifications.index', compact('notifications', 'unreadCount'));
    }

    /**
     * Mark notification as read
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure user owns this notification
        if ($notification->user_id !== auth()->id()) {
            abort(403, 'Unauthorized');
        }
        
        $notification->markAsRead();
        
        return response()->json([
            'success' => true,
            'message' => 'Notification marked as read.',
        ]);
    }

    /**
     * Mark all notifications as read
     */
    public function markAllAsRead()
    {
        auth()->user()->notifications()->unread()->update([
            'read' => true,
            'read_at' => now(),
        ]);
        
        return response()->json([
            'success' => true,
            'message' => 'All notifications marked as read.',
        ]);
    }

    /**
     * Get unread count
     */
    public function unreadCount()
    {
        $count = auth()->user()->unreadNotifications()->count();
        
        return response()->json([
            'count' => $count,
        ]);
    }
}
