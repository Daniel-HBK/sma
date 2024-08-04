<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('messages', function (Blueprint $table) {
            $table->id();
            $table->uuid('identifier')->unique();
            $table->text('content');
            $table->string('decryption_key_hash');
            $table->enum('expiry_type', ['read_once', 'time_based']);
            $table->timestamp('expiry_time')->nullable();
            $table->timestamps();
        });

        Schema::create('recipients', function (Blueprint $table) {
            $table->id();
            $table->string('identifier')->unique();
            $table->string('name');
            $table->timestamps();
        });

        Schema::create('message_recipients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('message_id')->constrained()->onDelete('cascade');
            $table->foreignId('recipient_id')->constrained()->onDelete('cascade');
            $table->boolean('has_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('message_recipients');
        Schema::dropIfExists('recipients');
        Schema::dropIfExists('messages');
    }
};
