<?php

namespace App\Services\V1;

use App\Factories\V1\MessageFactory;
use App\Repositories\V1\Interfaces\MessageRepositoryInterface;
use App\Repositories\V1\Interfaces\RecipientRepositoryInterface;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MessageService
{
    private MessageRepositoryInterface $messageRepository;
    private RecipientRepositoryInterface $recipientRepository;

    public function __construct(
        MessageRepositoryInterface $messageRepository,
        RecipientRepositoryInterface $recipientRepository
    ) {
        $this->messageRepository = $messageRepository;
        $this->recipientRepository = $recipientRepository;
    }

    public function createMessage(array $data): array
    {
        $decryptionKey = Str::random(32);
        $encryptedContent = $this->encrypt($data['content'], $decryptionKey);
        $identifier = Str::uuid()->toString();

        $message = MessageFactory::create([
            'identifier' => $identifier,
            'content' => $encryptedContent,
            'decryption_key_hash' => Hash::make($decryptionKey),
            'expiry_type' => $data['expiry_type'],
            'expiry_time' => $data['expiry_type'] === 'time_based' ? $data['expiry_time'] : null,
        ]);

        $savedMessage = $this->messageRepository->create($message->toArray());

        // Associate recipients
        foreach ($data['recipients'] as $recipientIdentifier) {
            $recipient = $this->recipientRepository->findOrCreateByIdentifier($recipientIdentifier);
            $savedMessage->recipients()->attach($recipient->id, ['created_at' => now(), 'has_read' => false]);
        }

        return [
            'identifier' => $identifier,
            'decryption_key' => $decryptionKey
        ];
    }


    public function retrieveMessage(string $identifier, string $recipientIdentifier, string $decryptionKey): ?array
    {
        $message = $this->messageRepository->findByIdentifier($identifier);

        if (!$message || !Hash::check($decryptionKey, $message->decryption_key_hash)) {
            return null;
        }

        $recipient = $this->recipientRepository->findByIdentifier($recipientIdentifier);

        if (!$recipient || !$message->recipients->contains($recipient)) {
            return null;
        }

        // Update the has_read status
        $this->messageRepository->markAsRead($message, $recipient);

        $decryptedContent = $this->decrypt($message->content, $decryptionKey);

        // Handle message expiry
        if ($message->expiry_type === 'read_once') {
            $this->messageRepository->delete($message);
        } elseif ($message->expiry_type === 'time_based' && $message->expiry_time->isPast()) {
            $this->messageRepository->delete($message);
            return null;
        }

        return [
            'content' => $decryptedContent,
            'created_at' => $message->created_at,
            'expiry_type' => $message->expiry_type,
            'expiry_time' => $message->expiry_time,
        ];
    }

    private function encrypt(string $content, string $key): string
    {
        $iv = openssl_random_pseudo_bytes(openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = openssl_encrypt($content, 'aes-256-cbc', $key, 0, $iv);
        return base64_encode($iv . $encrypted);
    }

    private function decrypt(string $encryptedContent, string $key): string
    {
        $data = base64_decode($encryptedContent);
        $iv = substr($data, 0, openssl_cipher_iv_length('aes-256-cbc'));
        $encrypted = substr($data, openssl_cipher_iv_length('aes-256-cbc'));
        return openssl_decrypt($encrypted, 'aes-256-cbc', $key, 0, $iv);
    }
}
