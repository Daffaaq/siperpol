<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AlertAndMessagesEvent
{
    use Dispatchable, SerializesModels;

    public $title;
    public $message;
    public $user_id;

    /**
     * Create a new event instance.
     *
     * @param  string  $title
     * @param  string  $message
     * @param  int  $user_id
     * @return void
     */
    public function __construct($title, $message, $user_id)
    {
        $this->title = $title;
        $this->message = $message;
        $this->user_id = $user_id;
    }
}
