<?php

namespace App\Shared\Application;

use Exception;

class NotValidIdDataException extends Exception
{
    private const MESSAGE = 'The data for id is not valid';

    public static function create(): self
    {
        return new self(self::MESSAGE);
    }
}
