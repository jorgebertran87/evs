<?php

namespace App\LocateGroup\Application;

use App\Shared\Application\Query;

class LocateGroupQuery implements Query
{
    public function __construct(private mixed $idData)
    {
    }

    public function idData(): mixed
    {
        return $this->idData;
    }
}
