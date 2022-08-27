<?php

namespace App\LocateGroup\Domain;

use App\Shared\Domain\Group;
use App\Shared\Domain\Id;

interface GroupsRepository
{
    public function findOneById(Id $id): Group|null;
}
