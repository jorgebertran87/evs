<?php

namespace App\DropOffGroup\Application;

use App\Shared\Application\Command;

class DropOffGroupCommand implements Command
{
    public function __construct(private mixed $groupData)
    {
    }

    public function groupData(): mixed
    {
        return $this->groupData;
    }
}
