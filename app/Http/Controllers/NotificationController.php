<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    /**
     * Display a listing of notifications.
     */
    public function index(Request $request)
    {
        $query = Auth::user()->notifications()->orderBy('created_at', 'desc');

        // Filter by read/unread
        if ($request->has('filter')) {
            if ($request->filter === 'unread') {
                $query->unread();
            } elseif ($request->filter === 'read') {
                $query->whereNotNull('read_at');
            }
        }

        $notifications = $query->paginate(20);

        return view('notifications.index', compact('notifications'));
    }

    /**
     * Mark notification as read.
     */
    public function markAsRead(Notification $notification)
    {
        // Ensure user owns this notification
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->markAsRead();

        // Redirect to support request if data contains support_request_id
        if (isset($notification->data['support_request_id'])) {
            // Superadmins go to admin panel, regular users go to their view
            if (Auth::user()->isSuperAdmin()) {
                return redirect()->route('superadmin.support.show', $notification->data['support_request_id'])
                    ->with('success', 'Notificación marcada como leída');
            } else {
                return redirect()->route('support.show', $notification->data['support_request_id'])
                    ->with('success', 'Notificación marcada como leída');
            }
        }

        return redirect()->back();
    }

    /**
     * Mark all notifications as read.
     */
    public function markAllAsRead()
    {
        Auth::user()->notifications()->unread()->update(['read_at' => now()]);

        return redirect()->back()->with('success', 'Todas las notificaciones han sido marcadas como leídas');
    }

    /**
     * Remove the specified notification.
     */
    public function destroy(Notification $notification)
    {
        // Ensure user owns this notification
        if ($notification->user_id !== Auth::id()) {
            abort(403);
        }

        $notification->delete();

        return redirect()->back()->with('success', 'Notificación eliminada');
    }
}
