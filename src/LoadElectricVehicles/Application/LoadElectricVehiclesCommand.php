<?php

namespace App\LoadElectricVehicles\Application;

use App\Shared\Application\Command;

final class LoadElectricVehiclesCommand implements Command
{
    public function __construct(private mixed $electricVehiclesData)
    {
    }

    public function electricVehiclesData(): mixed
    {
        return $this->electricVehiclesData;
    }
}
