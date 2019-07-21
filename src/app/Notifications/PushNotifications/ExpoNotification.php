<?php
namespace App\Notifications\PushNotifications;

use NotificationChannels\ExpoPushNotifications\ExpoChannel;
use NotificationChannels\ExpoPushNotifications\ExpoMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Bus\Queueable;

class ExpoNotification extends Notification implements ShouldQueue {
    use Queueable;

    public const TTL = 150;

    /**
     * @var string
     */
    protected $title;

    /**
     * @var string
     */
    protected $body;

    /**
     * @var array
     */
    protected $data;

    public function via($notifiable)
    {
        return [
            ExpoChannel::class
        ];
    }

    public function toExpoPush($notifiable)
    {
        return ExpoMessage::create()
            ->title($this->title)
            ->body($this->body)
            ->setJsonData($this->data)
            ->setTtl(self::TTL);
    }
}
