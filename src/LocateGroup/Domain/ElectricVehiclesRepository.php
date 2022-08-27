<?php

namespace App\LocateGroup\Domain;

use App\Shared\Domain\ElectricVehicle;
use App\Shared\Domain\Group;

interface ElectricVehiclesRepository
{
    public function locate(Group $group): ElectricVehicle|null;
}
