<?php

namespace App\Http\Controllers;

use App\Models\Notification;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class NotificationController extends Controller
{
    public function addNotification(Request $request)
    {
        $notificationDate = Carbon::createFromFormat('d.m.Y H:i', $request->input('notificationDate'))
            ->format('Y-m-d H:i:s');
        $notificationInfo = $request->input('notificationInfo');
        $leadId = $request->input('leadId');

        $notification = new Notification();
        $notification->time = $notificationDate;
        $notification->message = $notificationInfo;
        $notification->user_id = Auth::user()->id;
        $notification->lead_id = $leadId;

        $notification->save();

        return response()->json(['message' => 'Notification added successfully', 'notificationId' => $notification->id]);
    }

    public function deleteNotification($id)
    {
        $notification = Notification::find($id);

        if ($notification) {
            $notification->delete();
            return redirect()->back()->with('success', 'Уведомление успешно удалено!');
        } else {
            return redirect()->back()->with('errors', 'Уведомление не найдено!');
        }
    }

    public function getNotifications()
    {
        $notifications = Notification::with('lead')
            ->where('user_id', Auth::user()->id)
            ->where('time', '>', now())
            ->orderBy('time')
            ->get();

        $enhancedNotifications = $notifications->map(function ($notification) {
            if ($notification->lead) {
                $notification->name = $notification->lead->name;
            }

            return $notification;
        });

        return response()->json($enhancedNotifications);
    }

    public function notificationWasViewed($id)
    {
        $notification = Notification::find($id);

        if ($notification && $notification->user_id == Auth::id()) {

            $notification->is_viewed = true;
            $notification->save();

            return response()->json(['success' => 'Уведомление помечено как просмотренное']);
        }
    }

}
