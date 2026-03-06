<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function index()
    {
        $user = $this->getAuthenticatedUser();

        if (!$user) {
            return response()->json([]);
        }

        return response()->json($user->unreadNotifications);
    }

    public function markAsRead(Request $request)
    {
        $user = $this->getAuthenticatedUser();

        if ($user) {
            $query = DatabaseNotification::where('notifiable_id', $user->id)
                ->where('notifiable_type', get_class($user))
                ->whereNull('read_at');

            if ($request->has('ids') && is_array($request->ids)) {
                $query->whereIn('id', $request->ids);
            }

            $query->update(['read_at' => now()]);
        }

        return response()->json(['success' => true]);
    }

    private function getAuthenticatedUser()
    {
        if (Auth::guard('client')->check()) {
            return Auth::guard('client')->user();
        } elseif (Auth::guard('beautician')->check()) {
            return Auth::guard('beautician')->user();
        }
        return null;
    }
}
