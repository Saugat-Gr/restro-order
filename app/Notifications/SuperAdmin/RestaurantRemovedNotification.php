<?php

namespace App\Notifications\SuperAdmin;

use App\Models\Restaurant;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class RestaurantRemovedNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct(public Restaurant $restaurant)
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
        return ['database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    // public function toMail(object $notifiable): MailMessage
    // {
    //     return (new MailMessage)
    //                 ->line('The introduction to the notification.')
    //                 ->action('Notification Action', url('/'))
    //                 ->line('Thank you for using our application!');
    // }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'title' => 'Restaurant Removed',
            'message' => "Restaurant {$this->restaurant->name} removed",
            'data' => [
                "restaurant_name" => $this->restaurant->name,
                "restaurant_email" => $this->restaurant->email,
                "owner_name" => !empty($this->restaurant->user->name) ? $this->restaurant->user->name : 'NOT ASSIGNED',
                "owner_email" => !empty($this->restaurant->user->email) ? $this->restaurant->user->name : 'NOT ASSIGNED',

            ]
        ];
    }
}
