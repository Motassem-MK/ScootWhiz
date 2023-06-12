<?php

declare(strict_types=1);

namespace App\Http\Controllers\Scooter\Trip;

use App\Client\Exception\ClientNotFound;
use App\Client\Repository\ClientRepository;
use App\Http\Controllers\Controller;
use App\Http\Request\Scooter\Trip\BeginTripRequest;
use App\Scooter\Exception\ScooterNotFound;
use App\Scooter\Repository\ScooterRepository;
use App\Scooter\State\Validator\Exception\ScooterStateChangeException;
use App\Trip\Event\TripStarting;
use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class BeginTripController extends Controller
{
    public function __construct(
        private readonly ScooterRepository $scooterRepository,
        private readonly ClientRepository $clientRepository,
        private readonly Connection $connection,
    ) {
    }

    #[OA\Post(
        path: '/scooter/trip/begin',
        summary: 'Start trip',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['scooter_uuid', 'client_uuid', 'lat', 'long'],
                properties: [
                    new OA\Property(
                        property: 'scooter_uuid',
                        type: 'string',
                        format: 'uuid',
                        example: '25e4f3b6-23d7-3abb-8ec5-8739d2299e0c'
                    ),
                    new OA\Property(
                        property: 'client_uuid',
                        type: 'string',
                        format: 'uuid',
                        example: '25e4f3b6-23d7-3abb-8ec5-8739d2299e0c'
                    ),
                    new OA\Property(property: 'lat', type: 'number', format: 'float', example: '-15.323400'),
                    new OA\Property(property: 'long', type: 'number', format: 'float', example: '88.422053')
                ],
            ),
        ),
        tags: ['Scooter'],
        parameters: [
            new OA\Parameter(name: 'Content-Type', in: 'header', required: true, example: 'application/json'),
            new OA\Parameter(name: 'Accept', in: 'header', required: true, example: 'application/json'),
            new OA\Parameter(name: 'Authorization', in: 'header', required: true, example: 'abc123'),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Success',
            ),
            new OA\Response(
                response: Response::HTTP_UNPROCESSABLE_ENTITY,
                description: 'Unprocessable entity',
                content: new OA\JsonContent(ref: '#/components/schemas/Error422'),
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Unauthorized',
                content: new OA\JsonContent(ref: '#/components/schemas/Error401'),
            ),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error'),
        ],
    )]
    public function __invoke(BeginTripRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();
        try {
            $scooter = $this->scooterRepository->findByUuidAndLock($request->getScooterUuid());
            $client = $this->clientRepository->findByUuid($request->getClientUuid());

            TripStarting::dispatch($scooter, $client, $request->getStartingCoordinates(), Carbon::now());

            $this->connection->commit();

            return new JsonResponse();
        } catch (ScooterNotFound) {
            $this->connection->rollBack();

            return new JsonResponse(['error' => 'Scooter not found'], Response::HTTP_NOT_FOUND);
        } catch (ClientNotFound $e) {
            $this->connection->rollBack();

            return new JsonResponse(['error' => 'Client not found'], Response::HTTP_NOT_FOUND);
        } catch (ScooterStateChangeException $e) {
            $this->connection->rollBack();

            return new JsonResponse(
                ['error' => 'Cannot start scooter', 'reason' => $e->getMessage()],
                Response::HTTP_CONFLICT,
            );
        } catch (Throwable $e) {
            $this->connection->rollBack();

            return new JsonResponse(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
