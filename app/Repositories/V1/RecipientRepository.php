<?php

namespace App\Repositories\V1;

use App\Models\V1\Recipient;
use App\Repositories\V1\Interfaces\RecipientRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RecipientRepository implements RecipientRepositoryInterface
{

    use RefreshDatabase;

    protected $messageRepository;

    protected function setUp(): void
    {
        $this->messageRepository = new MessageRepository();
    }

    public function create(array $data): Recipient
    {
        return Recipient::create($data);
    }

    public function findByIdentifier(string $identifier): ?Recipient
    {
        return Recipient::where('identifier', $identifier)->first();
    }

    public function findOrCreateByIdentifier(string $identifier): Recipient
    {
        return Recipient::firstOrCreate(['identifier' => $identifier]);
    }

    public function update(Recipient $recipient, array $data): bool
    {
        return $recipient->update($data);
    }

    public function delete(Recipient $recipient): bool
    {
        return $recipient->delete();
    }

    public function getAllRecipients(): Collection
    {
        return Recipient::all();
    }

    public function getRecipientsWithUnreadMessages(): Collection
    {
        return Recipient::whereHas('messages', function ($query) {
            $query->whereHas('messageRecipients', function ($q) {
                $q->where('has_read', false);
            });
        })->get();
    }

    public function getRecipientMessageCount(Recipient $recipient): int
    {
        return $recipient->messages()->count();
    }

    public function searchRecipients(string $searchTerm): Collection
    {
        return Recipient::where('name', 'like', "%{$searchTerm}%")
            ->orWhere('identifier', 'like', "%{$searchTerm}%")
            ->get();
    }

    public function getRecentlyActiveRecipients(int $days = 30): Collection
    {
        return Recipient::whereHas('messages', function ($query) use ($days) {
            $query->where('created_at', '>=', now()->subDays($days));
        })->get();
    }
}
