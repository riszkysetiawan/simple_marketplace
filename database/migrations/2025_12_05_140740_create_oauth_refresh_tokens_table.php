<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('oauth_refresh_tokens', function (Blueprint $table) {
            $table->char('id', 80)->primary();
            $table->char('access_token_id', 80);
            $table->boolean('revoked')->default(false);
            $table->dateTime('expires_at')->nullable();

            // Foreign key
            $table->foreign('access_token_id')
                ->references('id')
                ->on('oauth_access_tokens')
                ->onDelete('cascade');

            // Indexes
            $table->index('revoked');
            $table->index('expires_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('oauth_refresh_tokens');
    }

    public function getConnection(): ?string
    {
        return $this->connection ?? config('passport.connection');
    }
};
