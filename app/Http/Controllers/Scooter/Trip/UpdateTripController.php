<?php

declare(strict_types=1);

namespace App\Http\Controllers\Scooter\Trip;

use App\Http\Controllers\Controller;
use App\Http\Request\Scooter\Trip\UpdateTripRequest;
use App\Trip\Event\TripUpdated;
use App\Trip\Exception\OngoingTripNotFound;
use App\Trip\Repository\TripRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class UpdateTripController extends Controller
{
    public function __construct(private readonly TripRepository $tripRepository)
    {
    }

    #[OA\Post(
        path: '/scooter/trip/update',
        summary: 'Update trip',
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
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error'),
        ],
    )]
    public function __invoke(UpdateTripRequest $request): JsonResponse
    {
        try {
            $trip = $this->tripRepository->findOngoingForScooter($request->getScooterUuid());

            TripUpdated::dispatch($trip, $request->getUpdatedCoordinates(), Carbon::now());

            return new JsonResponse();
        } catch (OngoingTripNotFound) {
            return new JsonResponse(
                ['error' => 'No ongoing trip was found for this scooter'],
                Response::HTTP_CONFLICT,
            );
        }
    }
}
