<?php

namespace App\Helpers;

use App\Notifications\GeneralNotification;
use Illuminate\Support\Facades\Notification;

class NotifyHelper
{
    /**
     * Send a general notification to one or more notifiables.
     *
     * @param \Illuminate\Support\Collection|\Illuminate\Notifications\Notifiable|array $notifiables
     * @param array $data
     * @param array $channels
     * @return void
     */
    public static function send($notifiables, array $data, array $channels = ['mail'])
    {
        Notification::send($notifiables, new GeneralNotification($data, $channels));
    }
}
