<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\ScooterLocation\Repository\Eloquent\Model\ScooterLocation;
use App\Trip\Repository\Eloquent\Model\Trip;
use Carbon\Carbon;
use Database\Factories\ClientFactory;
use Database\Factories\ScooterFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\EloquentModelHelper;
use Tests\TestCase;

class StartingTripTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/scooter/trip/begin';

    public function testShouldStartTrip(): void
    {
        $scooter = (new ScooterFactory())->available()->create();
        $client = (new ClientFactory())->create();
        $lat = 40.7128564;
        $long = 42.7128392;
        $startTime = Carbon::create(2023, 5, 10, 10, 5, 30);

        $this->travelTo($startTime);

        $this
            ->post(self::ENDPOINT, [
                'scooter_uuid' => $scooter->uuid,
                'client_uuid' => $client->uuid,
                'lat' => $lat,
                'long' => $long,
            ], [
                'Accept' => 'application/json',
                'Authorization' => self::API_KEY,
            ])
            ->assertOk();

        $this->assertDatabaseCount(EloquentModelHelper::getTableName(Trip::class), 1);
        $this->assertDatabaseCount(EloquentModelHelper::getTableName(ScooterLocation::class), 1);
        $this->assertDatabaseHas(EloquentModelHelper::getTableName(Trip::class), [
            'scooter_uuid' => $scooter->uuid,
            'client_uuid' => $client->uuid,
            'started_at' => $startTime,
        ]);
        $this->assertDatabaseHas(EloquentModelHelper::getTableName(ScooterLocation::class), [
            'scooter_uuid' => $scooter->uuid,
            'latitude' => $lat,
            'longitude' => $long,
            'received_at' => $startTime,
        ]);
    }

    public function testShouldNotStartTripOnOccupiedScooter(): void
    {
        $scooter = (new ScooterFactory())->occupied()->create();
        $client = (new ClientFactory())->create();
        $lat = 40.7128564;
        $long = 42.7128392;

        $this
            ->post(self::ENDPOINT, [
                'scooter_uuid' => $scooter->uuid,
                'client_uuid' => $client->uuid,
                'lat' => $lat,
                'long' => $long,
            ], [
                'Accept' => 'application/json',
                'Authorization' => self::API_KEY,
            ])
            ->assertConflict();

        $this->assertDatabaseCount(EloquentModelHelper::getTableName(Trip::class), 0);
        $this->assertDatabaseCount(EloquentModelHelper::getTableName(ScooterLocation::class), 0);
    }
}
