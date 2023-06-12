<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('scooter_locations', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->foreignUuid('trip_uuid')->nullable()->references('uuid')->on('trips')->cascadeOnDelete();
            $table->foreignUuid('scooter_uuid')->references('uuid')->on('scooters')->cascadeOnDelete();
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamp('received_at');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scooter_locations');
    }
};
