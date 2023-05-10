<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class NotificationRecieved implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public $notigication;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($notigication)
    {
        $this->notigication = $notigication;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    public function broadcastOn()
    {
        return new  Channel('Notification');
        
    }
    public function broadcastAs()
{
    return 'Notification';
}
    public function broadcastWith()
    {
        return ["msg"=> $this->notigication];
    }
}