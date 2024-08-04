<?php

namespace Tests\Feature\Api\V1;

use App\Models\V1\Recipient;
use Tests\TestCase;

class SecureMessagingTest extends TestCase
{

    public function testCreateMessage()
    {
        $recipient = Recipient::factory()->create();

        $response = $this->postJson('/api/v1/messages', [
            'content' => 'This is a test message',
            'recipients' => [$recipient->identifier],
            'expiry_type' => 'read_once'
        ]);

        $response->assertStatus(201)
            ->assertJsonStructure([
                'message',
                'identifier',
                'decryption_key'
            ]);

        $this->assertDatabaseHas('messages', [
            'expiry_type' => 'read_once'
        ]);
    }

    public function testRetrieveMessage()
    {
        $recipient = Recipient::factory()->create();

        $createResponse = $this->postJson('/api/v1/messages', [
            'content' => 'This is a test message',
            'recipients' => [$recipient->identifier],
            'expiry_type' => 'read_once'
        ]);

        $identifier = $createResponse->json('identifier');
        $decryptionKey = $createResponse->json('decryption_key');

        $response = $this->getJson("/api/v1/messages/{$identifier}?recipient_identifier={$recipient->identifier}&decryption_key={$decryptionKey}");

        $response->assertStatus(200)
            ->assertJsonStructure([
                'content',
                'created_at',
                'expiry_type'
            ]);

        // Attempt to retrieve the message again (should fail for 'read_once' messages)
        $secondResponse = $this->getJson("/api/v1/messages/{$identifier}?recipient_identifier={$recipient->identifier}&decryption_key={$decryptionKey}");

        $secondResponse->assertStatus(404);
    }

    public function testGetRecipientInfo()
    {
        $recipient = Recipient::factory()->create();

        $response = $this->getJson("/api/v1/recipients/{$recipient->identifier}");

        $response->assertStatus(200)
            ->assertJson([
                'identifier' => $recipient->identifier,
                'name' => $recipient->name
            ]);
    }

    public function testCreateMessageWithInvalidData()
    {
        $response = $this->postJson('/api/v1/messages', [
            'content' => 'This is a test message',
            'recipients' => [],  // Empty recipients array
            'expiry_type' => 'invalid_type'
        ]);

        $response->assertStatus(400);
    }

    public function testRetrieveNonExistentMessage()
    {
        $recipient = Recipient::factory()->create();
        $nonExistentIdentifier = 'e07de131-6e66-47cb-b1e4-afae90ecfe75';

        $response = $this->getJson("/api/v1/messages/{$nonExistentIdentifier}?recipient_identifier={$recipient->identifier}&decryption_key=some_key");

        $response->assertStatus(404);
    }
}