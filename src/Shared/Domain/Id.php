<?php

namespace App\Shared\Domain;

final class Id
{
    public function __construct(private string $identifier)
    {
    }

    public function __toString(): string
    {
        return $this->identifier;
    }

    public function equals(Id $id): bool
    {
        return (string) $this === (string) $id;
    }
}
