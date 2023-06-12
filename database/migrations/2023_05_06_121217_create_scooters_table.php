<?php

declare(strict_types=1);

use App\Scooter\State\Enum\State;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('scooters', function (Blueprint $table) {
            $table->uuid()->primary();
            $table->enum('state', array_column(State::cases(), 'value'));
            $table->decimal('latitude', 11, 8);
            $table->decimal('longitude', 11, 8);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('scooters');
    }
};
