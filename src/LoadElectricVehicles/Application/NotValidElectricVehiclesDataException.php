<?php

namespace App\LoadElectricVehicles\Application;

use Exception;

class NotValidElectricVehiclesDataException extends Exception
{
    private const MESSAGE = 'The data for electric vehicle is not valid';

    public static function create(): self
    {
        return new self(self::MESSAGE);
    }
}
