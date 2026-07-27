<?php

namespace App\Events;

use App\Models\LogUser;
use Illuminate\Broadcasting\Channel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivityLogged implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    /**
     * Create a new event instance.
     */
    public function __construct(
        public LogUser $log
    ) {
    }

    /**
     * Get the channels the event should broadcast on.
     */
    public function broadcastOn(): Channel
    {
        return new Channel('activities');
    }

    /**
     * Get the event name to broadcast as.
     */
    public function broadcastAs(): string
    {
        return 'activity.logged';
    }

    /**
     * Get the data to broadcast.
     */
    public function broadcastWith(): array
    {
        // Eager load the user and role relationship
        $this->log->load(['user.role']);

        return [
            'log' => $this->log->toArray()
        ];
    }
}
