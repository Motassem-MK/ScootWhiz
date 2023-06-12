<?php

declare(strict_types=1);

namespace App\Demo\Job;

use Faker\Generator;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;

class DemoTrip implements ShouldQueue
{
    use Dispatchable;

    use Queueable;

    private array $headers;

    public function __construct(private readonly string $clientUuid, private readonly string $scooterUuid)
    {
        $this->headers = ['Authorization' => Config::get('auth.api_key')];
    }

    public function handle(Generator $faker): void
    {
        /** @phpstan-ignore-next-line */
        while (true) {
            $currentLatitude = $faker->latitude(-89, 89);
            $currentLongitude = $faker->longitude(-179, 179);

            $this->startTrip($currentLatitude, $currentLongitude);

            $durationRange = Config::get('demo.trip_duration_range');
            $duration = mt_rand((int) $durationRange[0], (int) $durationRange[1]);
            $updateInterval = (int) Config::get('demo.update_interval');

            for ($time = 0; $time < $duration; $time += $updateInterval) {
                $currentLatitude = $this->addLinearStep($currentLatitude);
                $currentLongitude = $this->addLinearStep($currentLatitude);

                $this->updateTrip($currentLatitude, $currentLongitude);

                sleep($updateInterval);
            }

            $this->endTrip($currentLatitude, $currentLongitude);

            $restRange = Config::get('demo.rest_duration_range');
            $restDuration = mt_rand((int) $restRange[0], (int) $restRange[1]);
            sleep($restDuration);
        }
    }

    private function startTrip(float $lat, float $long): void
    {
        Http::withHeaders($this->headers)
            ->post('http://localhost/scooter/trip/begin', [
                'scooter_uuid' => $this->scooterUuid,
                'client_uuid' => $this->clientUuid,
                'lat' => $lat,
                'long' => $long,
            ]);
    }

    private function updateTrip(float $lat, float $long): void
    {
        Http::withHeaders($this->headers)
            ->post('http://localhost/scooter/trip/update', [
                'scooter_uuid' => $this->scooterUuid,
                'lat' => $lat,
                'long' => $long,
            ]);
    }

    private function endTrip(float $lat, float $long): void
    {
        Http::withHeaders($this->headers)
            ->post('http://localhost/scooter/trip/end', [
                'scooter_uuid' => $this->scooterUuid,
                'lat' => $lat,
                'long' => $long,
            ]);
    }

    private function addLinearStep(float $coordinate): float
    {
        return $coordinate + 0.0001;
    }
}
