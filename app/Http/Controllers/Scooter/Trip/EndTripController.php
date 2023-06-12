<?php

declare(strict_types=1);

namespace App\Http\Controllers\Scooter\Trip;

use App\Http\Controllers\Controller;
use App\Http\Request\Scooter\Trip\EndTripRequest;
use App\Scooter\State\Validator\Exception\ScooterStateChangeException;
use App\Trip\Event\TripEnding;
use App\Trip\Exception\OngoingTripNotFound;
use App\Trip\Repository\TripRepository;
use Carbon\Carbon;
use Illuminate\Database\Connection;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;
use Throwable;

class EndTripController extends Controller
{
    public function __construct(
        private readonly TripRepository $tripRepository,
        private readonly Connection $connection,
    ) {
    }

    #[OA\Post(
        path: '/scooter/trip/end',
        summary: 'End trip',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['scooter_uuid', 'lat', 'long'],
                properties: [
                    new OA\Property(
                        property: 'scooter_uuid',
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
            new OA\Response(
                response: Response::HTTP_CONFLICT,
                description: 'A conflict occurred while processing the request'
            ),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error'),
        ],
    )]
    public function __invoke(EndTripRequest $request): JsonResponse
    {
        $this->connection->beginTransaction();
        try {
            $trip = $this->tripRepository->findOngoingForScooter($request->getScooterUuid());

            TripEnding::dispatch($trip, $request->getParkedCoordinates(), Carbon::now());

            $this->connection->commit();

            return new JsonResponse();
        } catch (OngoingTripNotFound) {
            $this->connection->rollBack();

            return new JsonResponse(
                ['error' => 'No ongoing trip was found for this scooter'],
                Response::HTTP_UNPROCESSABLE_ENTITY
            );
        } catch (ScooterStateChangeException $e) {
            $this->connection->rollBack();

            return new JsonResponse(
                ['error' => 'Cannot stop scooter', 'reason' => $e->getMessage()],
                Response::HTTP_CONFLICT,
            );
        } catch (Throwable) {
            $this->connection->rollBack();

            return new JsonResponse(['error' => 'Internal server error'], Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
