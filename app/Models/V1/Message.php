<?php

namespace App\Models\V1;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class Message extends Model
{
    use HasFactory;

    protected $fillable = [
        'identifier',
        'content',
        'decryption_key_hash',
        'expiry_type',
        'expiry_time',
    ];

    protected $casts = [
        'expiry_time' => 'datetime',
    ];

    public function recipients()
    {
        return $this->belongsToMany(Recipient::class, 'message_recipients');
    }
}
