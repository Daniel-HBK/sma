<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\V1\Recipient;

class RecipientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $recipients = [
            ['identifier' => 'recipient1@example.com', 'name' => 'Recipient One'],
            ['identifier' => 'recipient2@example.com', 'name' => 'Recipient Two'],
        ];

        foreach ($recipients as $recipient) {
            Recipient::firstOrCreate(
                ['identifier' => $recipient['identifier']],
                ['name' => $recipient['name']]
            );
        }
    }
}
