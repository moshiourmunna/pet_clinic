<?php

namespace App\Notifications\Channels;

use App\Services\Sms\SmsManager;
use Illuminate\Notifications\Notification;

class SmsChannel
{
    public function __construct(private SmsManager $sms) {}

    public function send(object $notifiable, Notification $notification): void
    {
        $to = $notifiable->routeNotificationFor('sms', $notification);
        if ($to && method_exists($notification, 'toSms')) {
            $this->sms->send($to, $notification->toSms($notifiable));
        }
    }
}
