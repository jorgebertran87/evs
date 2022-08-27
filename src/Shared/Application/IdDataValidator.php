<?php

namespace App\Shared\Application;

class IdDataValidator
{
    /**
     * @throws NotValidIdDataException
     */
    public function validate(mixed $idData): void
    {
        $this->validateDataIdField($idData);
    }

    /**
     * @throws NotValidIdDataException
     */
    private function validateDataIdField(mixed $idData): void
    {
        if (!is_object($idData)) {
            throw NotValidIdDataException::create();
        }

        if (!property_exists($idData, 'id') || !is_int($idData->id)) {
            throw NotValidIdDataException::create();
        }
    }
}
