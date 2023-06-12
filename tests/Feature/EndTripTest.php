<?php

declare(strict_types=1);

namespace Tests\Feature;

use App\ScooterLocation\Repository\Eloquent\Model\ScooterLocation;
use App\Trip\Repository\Eloquent\Model\Trip;
use Carbon\Carbon;
use Database\Factories\ClientFactory;
use Database\Factories\ScooterFactory;
use Database\Factories\ScooterLocationFactory;
use Database\Factories\TripFactory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\Helpers\EloquentModelHelper;
use Tests\TestCase;

class EndTripTest extends TestCase
{
    use RefreshDatabase;

    private const ENDPOINT = '/scooter/trip/end';

    public function testShouldEndTrip(): void
    {
        $scooter = (new ScooterFactory())->occupied()->create();
        $client = (new ClientFactory())->create();
        $lat = 40.7128564;
        $long = 42.7128392;
        $startTime = Carbon::create(2023, 5, 10, 10, 5, 30);
        $endTime = $startTime->copy()->addMinutes(5);

        $trip = (new TripFactory())->create([
            'scooter_uuid' => $scooter->uuid,
            'client_uuid' => $client->uuid,
            'started_at' => $startTime,
        ]);

        (new ScooterLocationFactory())->create([
            'scooter_uuid' => $scooter->uuid,
            'trip_uuid' => $trip->uuid,
            'latitude' => 40.7128550,
            'longitude' => 42.7128400,
            'received_at' => $startTime,
        ]);

        $this->travelTo($endTime);

        $this
            ->post(self::ENDPOINT, [
                'scooter_uuid' => $scooter->uuid,
                'lat' => $lat,
                'long' => $long,
            ], [
                'Accept' => 'application/json',
                'Authorization' => self::API_KEY,
            ])
            ->assertOk();

        $this->assertDatabaseCount(EloquentModelHelper::getTableName(Trip::class), 1);
        $this->assertDatabaseCount(EloquentModelHelper::getTableName(ScooterLocation::class), 2);
        $this->assertDatabaseHas(EloquentModelHelper::getTableName(Trip::class), [
            'scooter_uuid' => $scooter->uuid,
            'client_uuid' => $client->uuid,
            'started_at' => $startTime,
            'ended_at' => $endTime,
        ]);
        $this->assertDatabaseHas(EloquentModelHelper::getTableName(ScooterLocation::class), [
            'scooter_uuid' => $scooter->uuid,
            'latitude' => $lat,
            'longitude' => $long,
            'received_at' => $endTime,
        ]);
    }

    public function testShouldNotEndAnAlreadyEndedTrip(): void
    {
        $scooter = (new ScooterFactory())->available()->create();
        $client = (new ClientFactory())->create();
        $lat = 40.7128564;
        $long = 42.7128392;
        $startTime = Carbon::create(2023, 5, 10, 10, 5, 30);

        (new TripFactory())->ended()->create([
            'scooter_uuid' => $scooter->uuid,
            'client_uuid' => $client->uuid,
            'started_at' => $startTime,
        ]);

        $this
            ->post(self::ENDPOINT, [
                'scooter_uuid' => $scooter->uuid,
                'lat' => $lat,
                'long' => $long,
            ], [
                'Accept' => 'application/json',
                'Authorization' => self::API_KEY,
            ])
            ->assertUnprocessable();

        $this->assertDatabaseCount(EloquentModelHelper::getTableName(Trip::class), 1);
        $this->assertDatabaseCount(EloquentModelHelper::getTableName(ScooterLocation::class), 0);
    }
}
