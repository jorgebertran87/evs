<?php

namespace App\PerformJourney\Application;

use Exception;

class NotValidGroupDataException extends Exception
{
    private const MESSAGE = 'The data for groups is not valid';

    public static function create(): self
    {
        return new self(self::MESSAGE);
    }
}
