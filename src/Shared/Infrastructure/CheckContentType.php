<?php

namespace App\Shared\Infrastructure;

class CheckContentType
{
    private const VALID_CONTENT_TYPE = 'json';

    public function __invoke(string|null $contentType): void
    {
        if (is_null($contentType) || self::VALID_CONTENT_TYPE !== $contentType) {
            throw InvalidContentTypeException::createFromContentType((string) $contentType, self::VALID_CONTENT_TYPE);
        }
    }
}
