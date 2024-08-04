<?php

namespace App\Services\V1;

use App\Repositories\V1\Interfaces\RecipientRepositoryInterface;


class RecipientService
{
    private RecipientRepositoryInterface $recipientRepository;

    public function __construct(
        RecipientRepositoryInterface $recipientRepository
    ) {
        $this->recipientRepository = $recipientRepository;
    }



    public function retrieveRecipientByIdentifier(string $recipientIdentifier): ?array
    {

        if (!$recipientIdentifier) {
            return null;
        }

        $recipient = $this->recipientRepository->findByIdentifier($recipientIdentifier);

        if (!$recipient) {
            return null;
        }

        return [
            'identifier' => $recipient->identifier,
            'name' => $recipient->name,
        ];
    }
}
