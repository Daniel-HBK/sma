<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Recipient extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'name',
    ];

    public function messages(): BelongsToMany
    {
        return $this->belongsToMany(Message::class, 'message_recipients')
            ->withPivot('has_read')
            ->withTimestamps();
    }
}
