<?php

namespace App\Repositories\V1\Interfaces;

use App\Models\V1\Recipient;
use Illuminate\Database\Eloquent\Collection;

interface RecipientRepositoryInterface
{
    public function create(array $data): Recipient;
    public function findByIdentifier(string $identifier): ?Recipient;
    public function findOrCreateByIdentifier(string $identifier): Recipient;
    public function update(Recipient $recipient, array $data): bool;
    public function delete(Recipient $recipient): bool;
    public function getAllRecipients(): Collection;
    public function getRecipientsWithUnreadMessages(): Collection;
    public function getRecipientMessageCount(Recipient $recipient): int;
    public function searchRecipients(string $searchTerm): Collection;
    public function getRecentlyActiveRecipients(int $days = 30): Collection;
}
