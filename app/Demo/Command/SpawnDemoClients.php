<?php

declare(strict_types=1);

namespace App\Demo\Command;

use App\Client\Repository\ClientRepository;
use App\Demo\Job\DemoTrip;
use App\Scooter\Repository\ScooterRepository;
use Database\Factories\ClientFactory;
use Database\Factories\ScooterFactory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Config;

class SpawnDemoClients extends Command
{
    protected $signature = 'demo:start {--persist}';
    protected $description = 'Spawn fake clients';

    public function handle(ScooterRepository $scooterRepository, ClientRepository $clientRepository): void
    {
        $clientsCount = (int) Config::get('demo.clients_count');

        /** @var string[] $scootersUuids */
        $scootersUuids = (new ScooterFactory())->count($clientsCount)->available()->create()->pluck(
            'uuid'
        )->toArray();

        /** @var string[] $clientsUuids */
        $clientsUuids = (new ClientFactory())->count($clientsCount)->create()->pluck('uuid')->toArray();

        if (!$this->option('persist')) {
            $this->cleanupOnExit($scootersUuids, $clientsUuids, $scooterRepository, $clientRepository);
        }

        foreach ($clientsUuids as $key => $clientUuid) {
            $assignedScooterUuid = $scootersUuids[$key];

            DemoTrip::dispatch($clientUuid, $assignedScooterUuid)->onQueue('demo');
        }

        $isFirstFrame = true;

        /** @phpstan-ignore-next-line */
        while (true) {
            $this->viewScooters($scootersUuids, $scooterRepository, $isFirstFrame);
            $isFirstFrame = false;
            sleep(1);
        }
    }

    /**
     * @param string[] $scootersUuids
     */
    private function viewScooters(array $scootersUuids, ScooterRepository $scooterRepository, bool $isFirstFrame): void
    {
        $headers = ['Scooter UUID', 'State', 'Geolocation (long, lat)'];

        $scooters = $scooterRepository->findManyByUuid($scootersUuids);

        $rows = [];
        foreach ($scooters as $scooter) {
            $rows[] = [
                $scooter->getUuid(),
                $scooter->getState()->value,
                sprintf('%s, %s', $scooter->getCoordinates()->longitude, $scooter->getCoordinates()->latitude),
            ];
        }

        if (!$isFirstFrame) {
            $this->clearPreviousTable(count($scooters));
        }
        $this->table($headers, $rows, 'borderless');
    }

    private function clearPreviousTable(int $lines): void
    {
        $headersLines = 3;
        for ($i = 0; $i <= $lines + $headersLines; $i++) {
            echo "\033[1A\033[K";
        }
    }

    private function cleanupOnExit(
        array $scootersUuids,
        array $clientsUuids,
        ScooterRepository $scooterRepository,
        ClientRepository $clientRepository,
    ): void {
        pcntl_signal(SIGINT, fn() => $this->clearDataAndQueueAndExit(
            $scootersUuids,
            $clientsUuids,
            $scooterRepository,
            $clientRepository,
        ));
        pcntl_signal(SIGTERM, fn() => $this->clearDataAndQueueAndExit(
            $scootersUuids,
            $clientsUuids,
            $scooterRepository,
            $clientRepository,
        ));
    }

    private function clearDataAndQueueAndExit(
        array $scootersUuids,
        array $clientsUuids,
        ScooterRepository $scooterRepository,
        ClientRepository $clientRepository,
    ): void {
        $this->info("Kill signal received");

        $this->info("Clearing generated DB records");
        $scooterRepository->deleteManyByUuid($scootersUuids);
        $clientRepository->deleteManyByUuid($clientsUuids);

        $this->info("Clearing demo queue");
        Artisan::call('queue:clear', ['--queue' => 'demo']);

        exit();
    }
}
