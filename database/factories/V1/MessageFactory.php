<?php

namespace Database\Factories\V1;

use App\Models\V1\Message;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class MessageFactory extends Factory
{
    protected $model = Message::class;

    public function definition()
    {
        return [
            'identifier' => Str::uuid()->toString(),
            'content' => $this->faker->sentence(),
            'decryption_key_hash' => bcrypt('test-key'),
            'expiry_type' => $this->faker->randomElement(['read_once', 'time_based']),
            'expiry_time' => $this->faker->optional()->dateTimeBetween('now', '+1 week'),
        ];
    }

    public function readOnce()
    {
        return $this->state(function (array $attributes) {
            return [
                'expiry_type' => 'read_once',
                'expiry_time' => null,
            ];
        });
    }

    public function timeBased()
    {
        return $this->state(function (array $attributes) {
            return [
                'expiry_type' => 'time_based',
                'expiry_time' => $this->faker->dateTimeBetween('now', '+1 week'),
            ];
        });
    }
}
