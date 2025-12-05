<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oauth_auth_codes', function (Blueprint $table) {
            $table->char('id', 80)->primary();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignUuid('client_id')->constrained('oauth_clients')->onDelete('cascade');
            $table->text('scopes')->nullable();
            $table->boolean('revoked')->default(false);
            $table->dateTime('expires_at')->nullable();

            $table->index('revoked');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_auth_codes');
    }

    public function getConnection(): ?string
    {
        return $this->connection ??  config('passport.connection');
    }
};
