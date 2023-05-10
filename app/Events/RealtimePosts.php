<?php

namespace App\Events;

use App\Models\Post;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class RealtimePosts implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    /**
     * Create a new event instance.
     *
     * @param Post $post
     */
    public   $post;
    public   $user;
    public function __construct( Post $post,$user)
    {
        $this->post = $post;
        $this->user = $user;
    }

    /**
     * Get the channels the event should broadcast on.
     *
     * @return \Illuminate\Broadcasting\Channel|array
     */
    // public function broadcastOn()
    // {
    //     return new   Channel('post-notification'.$this->post->id);
    // }
    public function broadcastOn()
    {
        return      ['post.notification'];
    }
    // public function broadcastAs(){
    //     return 'post.added';
    // }
    // public function broadcastWith(){
    //     return [
    //         'post'=>$this->post,
    //         // 'post_id'=>$this->post->id,
    //         // 'post_content'=>$this->post->content,
    //             'user_id'=> $this->user->id,
    //             'user_name'=> $this->user->name,
    // ];
    // }
}
