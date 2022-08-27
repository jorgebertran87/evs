<?php

namespace Tests\Shared;

use App\Shared\Domain\Group;
use App\Shared\Domain\Id;
use Faker\Factory;

class GroupsMother
{
    public static function random(): Group
    {
        $faker = Factory::create();

        return new Group(id: new Id((string) $faker->numberBetween()), people: $faker->numberBetween());
    }
}
