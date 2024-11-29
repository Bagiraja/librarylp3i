<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function markAsRead(Request $request, $id)
    {
        $notification = DatabaseNotification::findOrFail($id);
        
        // Verify the notification belongs to the authenticated user
        if ($notification->notifiable_id !== Auth::id()) {
            abort(403);
        }
        
        $notification->markAsRead();
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back();
    }

    public function markAllAsRead(Request $request)
    {
        $user = Auth::user();
        DatabaseNotification::where('notifiable_id', $user->id)
            ->where('notifiable_type', get_class($user))
            ->whereNull('read_at')
            ->update(['read_at' => now()]);
        
        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }
        
        return back();
    }
    public function unreadCount()
    {
        // Menggunakan DatabaseNotification langsung
        $count = DatabaseNotification::where('notifiable_id', auth()->id())
            ->where('notifiable_type', get_class(auth()->user()))
            ->whereNull('read_at')
            ->count();
            
        return response()->json(['unread_count' => $count]);
    }

}
