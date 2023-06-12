<?php

return [
    'clients_count' => env('DEMO_CLIENTS_COUNT', 3),
    'update_interval' => env('DEMO_UPDATE_INTERVAL', 3),
    'trip_duration_range' => [env('DEMO_TRIP_DURATION_RANGE_FROM', 10), env('DEMO_TRIP_DURATION_RANGE_TO', 15)],
    'rest_duration_range' => [env('DEMO_REST_DURATION_RANGE_FROM', 2), env('DEMO_REST_DURATION_RANGE_TO', 5)],
];
