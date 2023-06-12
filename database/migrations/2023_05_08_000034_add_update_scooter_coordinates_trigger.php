<?php

declare(strict_types=1);

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        DB::unprepared(
            '
            CREATE TRIGGER update_scooter_location
            AFTER INSERT ON scooter_locations
            FOR EACH ROW
            BEGIN
                UPDATE scooters
                SET latitude = NEW.latitude, longitude = NEW.longitude
                WHERE uuid = NEW.scooter_uuid;
            END;
            '
        );
    }

    public function down(): void
    {
        DB::unprepared('DROP TRIGGER update_scooter_location');
    }
};
