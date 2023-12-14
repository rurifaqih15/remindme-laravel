<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Reminder;

class ReminderNotification extends Notification
{
    use Queueable;

    private Reminder $reminder;
    /**
     * Create a new notification instance.
     */
    public function __construct(Reminder $reminder)
    {
            $this->reminder = $reminder;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $reminder = $this->reminder;
        return (new MailMessage)
                    ->line("{$reminder->user->name},")
                    ->line("Reminder for {$reminder->title}")
                    ->line("Reminder description:{$reminder->description}");
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
