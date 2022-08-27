<?php

namespace Tests\Shared;

use App\Shared\Domain\ElectricVehicle;
use App\Shared\Domain\Id;
use Faker\Factory;

class ElectricVehiclesMother
{
    public static function createWithSeats(int $seats): ElectricVehicle
    {
        $faker = Factory::create();

        return new ElectricVehicle(new Id((string) $faker->numberBetween()), $seats);
    }

    public static function random(): ElectricVehicle
    {
        $faker = Factory::create();

        return new ElectricVehicle(id: new Id((string) $faker->numberBetween()), seats: $faker->numberBetween());
    }
}
