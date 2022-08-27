<?php

namespace App\PerformJourney\Domain;

use App\Shared\Domain\ElectricVehicle;

interface ElectricVehiclesRepository
{
    /**
     * @return ElectricVehicle[]
     */
    public function getAll(): array;

    public function save(ElectricVehicle $electricVehicle): void;
}
