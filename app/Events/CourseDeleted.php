<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class CourseDeleted
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $course;

    public function __construct($course)
    {
        $this->course = $course;
    }

    public function broadcastOn()
    {
        return new PrivateChannel('channel-name');
    }

    public function shouldBroadcast()
    {
        return false;
    }
}

