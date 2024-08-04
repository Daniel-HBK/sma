<?php

namespace App\Repositories\V1;

use App\Models\V1\Message;
use App\Models\V1\Recipient;
use App\Repositories\V1\Interfaces\MessageRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\DB;

class MessageRepository implements MessageRepositoryInterface
{
    public function create(array $data): Message
    {
        return Message::create($data);
    }

    public function associateRecipient(Message $message, string $recipientIdentifier): void
    {
        $message->recipients()->create(['identifier' => $recipientIdentifier]);
    }

    public function findByIdentifier(string $identifier): ?Message
    {
        return Message::where('identifier', $identifier)->first();
    }

    public function delete(Message $message): bool
    {
        return $message->delete();
    }

    public function getExpiredMessages(): Collection
    {
        return Message::where('expiry_type', 'time_based')
            ->where('expiry_time', '<', now())
            ->get();
    }


    public function markAsRead(Message $message, Recipient $recipient): bool
    {
        return $message->recipients()->updateExistingPivot($recipient->id, ['has_read' => true, 'updated_at' => now()]);
    }

    public function isReadByRecipient(Message $message, string $recipientIdentifier): bool
    {
        return DB::table('message_recipients')
            ->where('message_id', $message->id)
            ->where('recipient_identifier', $recipientIdentifier)
            ->where('has_read', true)
            ->exists();
    }

    public function getMessagesForRecipient(string $recipientIdentifier): Collection
    {
        return Message::whereHas('recipients', function ($query) use ($recipientIdentifier) {
            $query->where('identifier', $recipientIdentifier);
        })->get();
    }

    public function deleteExpiredMessages(): int
    {
        return Message::where('expiry_type', 'time_based')
            ->where('expiry_time', '<', now())
            ->delete();
    }

    public function updateExpiryTime(Message $message, \DateTime $newExpiryTime): bool
    {
        $message->expiry_time = $newExpiryTime;
        return $message->save();
    }

    public function getMessageStats(): array
    {
        return [
            'total_messages' => Message::count(),
            'read_once_messages' => Message::where('expiry_type', 'read_once')->count(),
            'time_based_messages' => Message::where('expiry_type', 'time_based')->count(),
            'expired_messages' => Message::where('expiry_type', 'time_based')
                ->where('expiry_time', '<', now())
                ->count(),
        ];
    }
}