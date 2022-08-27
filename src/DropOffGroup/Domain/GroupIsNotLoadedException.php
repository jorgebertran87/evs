<?php

namespace App\DropOffGroup\Domain;

use App\Shared\Domain\Id;
use Exception;

class GroupIsNotLoadedException extends Exception
{
    private const MESSAGE = 'This group is not loaded';

    public static function createFromId(Id $id): self
    {
        return new self(self::MESSAGE.': '.(string) $id);
    }
}
