<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class GeneralNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public array $data, public array $channels = ['mail'])
    {
        //
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
         return $this->channels;
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject($this->data['subject'] ?? '')
            ->greeting('Hello ' . $notifiable->name)
            ->line($this->data['message'] ?? '')
            ->action($this->data['action_text'] ?? 'View', $this->data['action_url'] ?? url('/'))
            ->line($this->data['footer'] ?? '');
    }



    public function toDatabase(object $notifiable)
    {
        return [
            'title' => $this->data['title'] ?? '',
            'message' => $this->data['message'] ?? '',
            'icon' => $this->data['icon'] ?? null,
            'url' => $this->data['url'] ?? null,
            'type' => $this->data['type'] ?? 'info',
        ];
    }

    public function toBroadcast(object $notifiable):BroadcastMessage
    {
        return new BroadcastMessage([
            'title' => $this->data['title'] ?? '',
            'message' => $this->data['message'] ?? '',
            'url' => $this->data['url'] ?? null,
            'type' => $this->data['type'] ?? 'info',
        ]);
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
