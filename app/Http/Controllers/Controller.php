<?php

declare(strict_types=1);

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;
use OpenApi\Attributes as OA;

#[OA\Info(version: "1.0.0", title: "Scootin-About API Documentation")]
#[OA\Components(
    schemas: [
        new OA\Schema(
            schema: 'Error422',
            properties: [
                new OA\Property(
                    property: 'message',
                    type: 'string',
                    example: 'The selected output.format is invalid. (and 1 more error)'
                ),
                new OA\Property(
                    property: 'errors',
                    properties: [
                        new OA\Property(
                            property: 'output.x',
                            type: 'array',
                            items: new OA\Items(type: 'string', example: 'The output.x name field is required.'),
                        ),
                    ],
                    type: 'object',
                ),
            ],
        ),
        new OA\Schema(
            schema: 'Error401',
            properties: [
                new OA\Property(property: 'message', type: 'string', example: 'Unauthenticated.'),
            ],
        ),
    ],
)]
class Controller extends BaseController
{
    use AuthorizesRequests;
    use ValidatesRequests;
}
