<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('trips', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('scooter_uuid')->references('uuid')->on('scooters')->cascadeOnDelete();
            $table->foreignUuid('client_uuid')->references('uuid')->on('clients');
            $table->timestamp('started_at');
            $table->timestamp('ended_at')->nullable();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('trips');
    }
};
