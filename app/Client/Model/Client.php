<?php

declare(strict_types=1);

namespace App\Client\Model;

readonly class Client
{
    public function __construct(private string $uuid)
    {
    }

    public static function fromArray(array $parameters): self
    {
        return new self(
            $parameters['uuid'],
        );
    }

    public function getUuid(): string
    {
        return $this->uuid;
    }
}
