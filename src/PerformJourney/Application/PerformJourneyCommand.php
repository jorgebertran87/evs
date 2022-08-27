<?php

namespace App\PerformJourney\Application;

use App\Shared\Application\Command;

class PerformJourneyCommand implements Command
{
    public function __construct(private mixed $groupData)
    {
    }

    public function groupData(): mixed
    {
        return $this->groupData;
    }
}
