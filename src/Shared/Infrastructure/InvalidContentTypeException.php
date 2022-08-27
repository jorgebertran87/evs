<?php

namespace App\Shared\Infrastructure;

use Exception;

class InvalidContentTypeException extends Exception
{
    private const MESSAGE = 'This content type [contentType] is not valid';

    public static function createFromContentType(string $contentType, string $validContenType): self
    {
        return new self(str_replace('[contentType]', $contentType, self::MESSAGE.': '.$validContenType));
    }
}
