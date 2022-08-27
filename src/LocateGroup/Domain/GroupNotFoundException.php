<?php

namespace App\LocateGroup\Domain;

use App\Shared\Domain\Id;
use Exception;

class GroupNotFoundException extends Exception
{
    private const MESSAGE = 'This group cannot be found';

    public static function createFromId(Id $id): self
    {
        return new self(self::MESSAGE.': '.(string) $id);
    }
}
