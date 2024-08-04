<?php

namespace Database\Factories\V1;

use App\Models\V1\Recipient;
use Illuminate\Database\Eloquent\Factories\Factory;

class RecipientFactory extends Factory
{
    protected $model = Recipient::class;

    public function definition()
    {
        return [
            'identifier' => $this->faker->unique()->safeEmail(),
            'name' => $this->faker->name(),
        ];
    }
}
