<?php

namespace App\PerformJourney\Domain;

use App\Shared\Domain\Group;

interface GroupsRepository
{
    public function save(Group $group): void;
}
