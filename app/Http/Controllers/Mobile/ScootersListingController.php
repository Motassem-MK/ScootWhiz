<?php

declare(strict_types=1);

namespace App\Http\Controllers\Mobile;

use App\Http\Controllers\Controller;
use App\Http\Request\Mobile\ListScootersRequest;
use App\Http\Resources\ScooterResource;
use App\Scooter\Repository\ScooterRepository;
use Illuminate\Contracts\Support\Responsable;
use OpenApi\Attributes as OA;
use Symfony\Component\HttpFoundation\Response;

class ScootersListingController extends Controller
{
    public function __construct(private readonly ScooterRepository $repository)
    {
    }

    #[OA\Post(
        path: '/mobile/scooters',
        summary: 'List scooters filtered by location & state',
        requestBody: new OA\RequestBody(
            required: true,
            content: new OA\JsonContent(
                required: ['lat1', 'long1', 'lat2', 'long2'],
                properties: [
                    new OA\Property(property: 'lat1', type: 'number', format: 'float', example: '-15.323334'),
                    new OA\Property(property: 'long1', type: 'number', format: 'float', example: '88.422053'),
                    new OA\Property(property: 'lat2', type: 'number', format: 'float', example: '-15.323400'),
                    new OA\Property(property: 'long2', type: 'number', format: 'float', example: '88.422350'),
                    new OA\Property(
                        property: 'state',
                        type: 'string',
                        enum: ["available", "occupied"],
                        example: 'available'
                    ),
                ],
            ),
        ),
        tags: ['Mobile'],
        parameters: [
            new OA\Parameter(name: 'Content-Type', in: 'header', required: true, example: 'application/json'),
            new OA\Parameter(name: 'Accept', in: 'header', required: true, example: 'application/json'),
            new OA\Parameter(name: 'Authorization', in: 'header', required: true, example: 'abc123'),
        ],
        responses: [
            new OA\Response(
                response: Response::HTTP_OK,
                description: 'Success',
                content: new OA\JsonContent(
                    properties: [
                        new OA\Property(
                            property: 'data',
                            type: 'array',
                            items: new OA\Items(
                                properties: [
                                    new OA\Property(
                                        property: 'uuid',
                                        type: 'string',
                                        format: 'uuid',
                                        example: '31878b1f-d6bd-3058-a4bd-76e68b81fc11'
                                    ),
                                    new OA\Property(
                                        property: 'state',
                                        type: 'string',
                                        enum: ['available', 'occupied'],
                                        example: 'available'
                                    ),
                                    new OA\Property(
                                        property: 'coordinates',
                                        properties: [
                                            new OA\Property(
                                                property: 'latitude',
                                                type: 'number',
                                                format: 'float',
                                                example: '-15.323385'
                                            ),
                                            new OA\Property(
                                                property: 'longitude',
                                                type: 'number',
                                                format: 'float',
                                                example: '88.422120'
                                            ),
                                        ],
                                        type: 'object'
                                    ),
                                ]
                            ),
                        ),
                    ],
                ),
            ),
            new OA\Response(
                response: Response::HTTP_UNAUTHORIZED,
                description: 'Unauthorized',
                content: new OA\JsonContent(ref: '#/components/schemas/Error401'),
            ),
            new OA\Response(response: Response::HTTP_INTERNAL_SERVER_ERROR, description: 'Internal server error'),
        ],
    )]
    public function __invoke(ListScootersRequest $request): Responsable
    {
        $scooters = $request->getState() ?
            $this->repository->searchByStateWithinCoordinates(
                $request->getFirstPoint(),
                $request->getSecondPoint(),
                $request->getState(),
            )
            : $this->repository->searchWithinCoordinates($request->getFirstPoint(), $request->getSecondPoint());

        return ScooterResource::collection($scooters);
    }
}
