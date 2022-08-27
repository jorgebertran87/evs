<?php

namespace App\DropOffGroup\Domain;

use App\Shared\Domain\Group;
use App\Shared\Domain\Id;

interface GroupsRepository
{
    public function findOneById(Id $id): Group|null;

    public function dropOff(Group $group): void;
}
