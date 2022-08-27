<?php

namespace App\LocateGroup\Domain;

use App\Shared\Domain\Id;
use Exception;

class GroupIsWaitingToBeAssignedException extends Exception
{
    private const MESSAGE = 'This group is waiting to be assigned';

    public static function createFromId(Id $id): self
    {
        return new self(self::MESSAGE.': '.(string) $id);
    }
}
