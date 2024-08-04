<?php

namespace App\Factories\V1;

use App\Models\V1\Message;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;

class MessageFactory
{
    public static function create(array $data): Message
    {
        return new Message([
            'identifier' => $data['identifier'],
            'content' => $data['content'],
            'decryption_key_hash' => $data['decryption_key_hash'],
            'expiry_type' => $data['expiry_type'],
            'expiry_time' => $data['expiry_time'],
        ]);
    }
}
