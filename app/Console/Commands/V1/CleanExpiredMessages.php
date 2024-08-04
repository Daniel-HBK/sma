<?php

namespace App\Console\Commands\V1;

use App\Repositories\V1\Interfaces\MessageRepositoryInterface;
use Illuminate\Console\Command;

class CleanExpiredMessages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'messages:clean-expired';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clean up expired messages';

    /**
     * @var MessageRepositoryInterface
     */
    private $messageRepository;

    /**
     * Create a new command instance.
     *
     * @param MessageRepositoryInterface $messageRepository
     */
    public function __construct(MessageRepositoryInterface $messageRepository)
    {
        parent::__construct();
        $this->messageRepository = $messageRepository;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $expiredMessages = $this->messageRepository->getExpiredMessages();

        foreach ($expiredMessages as $message) {
            $this->messageRepository->delete($message);
        }

        $this->info('(' . count($expiredMessages) . ') Expired messages cleaned up successfully.');
    }
}