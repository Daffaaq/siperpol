<?php

namespace App\Listeners;

use App\Events\AlertAndMessagesEvent;
use App\Models\Alert;
use App\Models\Message;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AlertAndMessagesListener
{
    /**
     * Handle the event.
     *
     * @param  \App\Events\AlertAndMessagesEvent  $event
     * @return void
     */
    public function handle(AlertAndMessagesEvent $event)
    {
        // Add alert notification
        Alert::create([
            'title' => $event->title,
            'message' => $event->message,
            'type' => 'info',
            'sended_at' => now(),
            'is_read' => false,
            'users_id' => $event->user_id
        ]);

        // Create message for the system
        Message::create([
            'sender' => 'System',
            'message' => $event->message,
            'status' => 'unread',
            'sended_time' => now(),
            'users_id' => $event->user_id
        ]);
    }
}
