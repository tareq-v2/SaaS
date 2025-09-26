<?php

namespace App\Events;

use App\Models\Task;
use App\Models\User;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TaskAssigned implements ShouldBroadcast
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $task;
    public $user;
    public $message;

    public function __construct(Task $task, User $user)
    {
        $this->task = $task;
        $this->user = $user;
        $this->message = "You have been assigned a new task: {$task->title}";
    }

    public function broadcastOn()
    {
        return new PrivateChannel('user.' . $this->user->id);
    }

    public function broadcastAs()
    {
        return 'task.assigned';
    }

    public function broadcastWith()
    {
        return [
            'task' => [
                'id' => $this->task->id,
                'title' => $this->task->title,
                'priority' => $this->task->priority,
                'due_date' => $this->task->due_date?->format('M d, Y'),
            ],
            'message' => $this->message,
            'timestamp' => now()->toDateTimeString(),
        ];
    }
}