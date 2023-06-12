<?php

declare(strict_types=1);

namespace Tests\Traits;

use Illuminate\Support\Facades\Config;

trait RequiresAuthentication
{
    public const API_KEY = 'fakeToken';

    public static function useTestKey(): void
    {
        Config::set('auth.api_key', self::API_KEY);
    }
}
