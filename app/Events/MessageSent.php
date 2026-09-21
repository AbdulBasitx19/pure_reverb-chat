<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Models\Message;

class MessageSent implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public $message;
    
    public function __construct(Message $message)
    {
        $this->message = $message;
    }

    public function broadcastOn(): array
    {
        $ids = [$this->message->sender_id, $this->message->receiver_id];
        sort($ids);
        return [
            new PrivateChannel('chat.' . $ids[0] . '.' . $ids[1]),
        ];
        // Note: PrivateChannel automatically adds 'private-' prefix
    }

    public function broadcastAs(): string 
    {
        return 'message.sent';
    }

    public function broadcastWith(): array 
    {
        // ✅ CRITICAL FIX: Guarantee that sender_name is never null
        $senderName = $this->message->sender 
                      ? $this->message->sender->name 
                      : auth()->user()->name; // Fallback to current user if relationship is null

        return [
            'id' => $this->message->id,
            'sender_id' => $this->message->sender_id,
            'sender_name' => $senderName, // Explicit string, not an object
            'receiver_id' => $this->message->receiver_id,
            'message' => $this->message->message,
            'created_at' => $this->message->created_at->toDateTimeString(),
        ];
    }
}