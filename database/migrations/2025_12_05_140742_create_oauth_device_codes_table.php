<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oauth_device_codes', function (Blueprint $table) {
            $table->char('id', 80)->primary();
            $table->foreignId('user_id')->nullable()->constrained('users')->onDelete('cascade');
            $table->foreignUuid('client_id')->constrained('oauth_clients')->onDelete('cascade');
            $table->char('user_code', 8)->unique();
            $table->text('scopes');
            $table->boolean('revoked')->default(false);
            $table->dateTime('user_approved_at')->nullable();
            $table->dateTime('last_polled_at')->nullable();
            $table->dateTime('expires_at')->nullable();

            // Indexes (user_id & client_id sudah auto-indexed)
            $table->index('user_code');
            $table->index('revoked');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_device_codes');
    }

    public function getConnection(): ?string
    {
        return $this->connection ??  config('passport.connection');
    }
};
