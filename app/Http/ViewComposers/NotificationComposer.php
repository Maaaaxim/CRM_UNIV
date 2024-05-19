<?php

namespace App\Http\ViewComposers;

use App\Models\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\View;

// Убедитесь, что модель импортирована правильно

class NotificationComposer
{
    public function compose(View $view)
    {
        $unviewedNotifications = Notification::with('lead')
            ->where('time', '<', now())
            ->where('is_viewed', false)
            ->where('user_id', Auth::id())
            ->get();

        $unviewedCount = $unviewedNotifications->count();

        $enhancedNotifications = $unviewedNotifications->map(function ($notification) {
            if ($notification->lead) {
                $notification->name = $notification->lead->name;
            }

            return $notification;
        });

        $view->with('notifications', $enhancedNotifications)
            ->with('unviewedCount', $unviewedCount);

    }
}
