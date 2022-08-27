<?php

namespace App\LoadElectricVehicles\Domain;

use App\Shared\Domain\ElectricVehicle;

interface ElectricVehiclesRepository
{
    public function unloadAll(): void;

    public function load(ElectricVehicle $electricVehicle): void;
}
