<?php

declare(strict_types=1);

namespace Tests\Helpers;

class EloquentModelHelper
{
    public static function getTableName(string $modelClass): string
    {
        return (new $modelClass())->getTable();
    }
}
