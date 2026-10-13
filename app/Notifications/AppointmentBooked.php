<?php

namespace App\Notifications;

use App\Models\Appointment;
use App\Notifications\Channels\SmsChannel;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class AppointmentBooked extends Notification
{
    public function __construct(public Appointment $appointment, public bool $forClinic = false) {}

    public function via(object $notifiable): array
    {
        $ch = [];
        if ($notifiable->routeNotificationFor('mail', $this)) $ch[] = 'mail';
        if (! $this->forClinic && $notifiable->routeNotificationFor('sms', $this)) $ch[] = SmsChannel::class;
        return $ch;
    }

    public function toMail(object $notifiable): MailMessage
    {
        $a = $this->appointment->loadMissing('service', 'doctor');
        return (new MailMessage)
            ->subject(($this->forClinic ? 'New booking ' : 'Your appointment ').$a->code)
            ->greeting($this->forClinic ? 'New appointment received' : 'Hello '.$a->owner_name.',')
            ->line('Booking code: '.$a->code)
            ->line('Service: '.$a->service->translate('name', 'en'))
            ->line('Doctor: '.($a->doctor?->translate('name', 'en') ?? 'To be assigned'))
            ->line('Date: '.$a->date->format('d M Y').($a->time ? ' at '.$a->time_label : ''))
            ->line('Status: '.ucfirst($a->status))
            ->action('View booking', route('appointment.success', $a->code));
    }

    public function toSms(object $notifiable): string
    {
        $a = $this->appointment;
        return "PetCare Clinic: booking {$a->code} ".($a->type === 'slot' ? 'confirmed' : 'received')
            .' for '.$a->date->format('d M').($a->time ? ' '.$a->time_label : '').'. Call +8801700000000 for help.';
    }
}
