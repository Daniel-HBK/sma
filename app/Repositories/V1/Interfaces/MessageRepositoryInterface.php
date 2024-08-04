<?php

namespace App\Repositories\V1\Interfaces;

use App\Models\V1\Message;
use App\Models\V1\Recipient;
use Illuminate\Database\Eloquent\Collection;

interface MessageRepositoryInterface
{
    /**
     * Create a new message.
     *
     * @param array $data
     * @return Message
     */
    public function create(array $data): Message;


    /**
     * Associates a recipient with a given message.
     *
     * @param Message $message The message to associate the recipient with.
     * @param string $recipientIdentifier The identifier of the recipient to associate.
     *
     * @return void
     */
    public function associateRecipient(Message $message, string $recipientIdentifier): void;

    /**
     * Find a message by its identifier.
     *
     * @param string $identifier
     * @return Message|null
     */
    public function findByIdentifier(string $identifier): ?Message;

    /**
     * Delete a message.
     *
     * @param Message $message
     * @return bool
     */
    public function delete(Message $message): bool;

    /**
     * Get all expired messages.
     *
     * @return Collection
     */
    public function getExpiredMessages(): Collection;

    /**
     * Mark a message as read for a specific recipient.
     *
     * @param Message $message
     * @param string $recipientIdentifier
     * @return bool
     */
    public function markAsRead(Message $message, Recipient $recipient): bool;

    /**
     * Check if a message has been read by a specific recipient.
     *
     * @param Message $message
     * @param string $recipientIdentifier
     * @return bool
     */
    public function isReadByRecipient(Message $message, string $recipientIdentifier): bool;

    /**
     * Get all messages for a specific recipient.
     *
     * @param string $recipientIdentifier
     * @return Collection
     */
    public function getMessagesForRecipient(string $recipientIdentifier): Collection;

    /**
     * Delete all expired messages.
     *
     * @return int The number of deleted messages
     */
    public function deleteExpiredMessages(): int;

    /**
     * Update the expiry time of a message.
     *
     * @param Message $message
     * @param \DateTime $newExpiryTime
     * @return bool
     */
    public function updateExpiryTime(Message $message, \DateTime $newExpiryTime): bool;

    /**
     * Get statistics about messages in the system.
     *
     * @return array
     */
    public function getMessageStats(): array;
}