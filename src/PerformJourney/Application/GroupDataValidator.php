<?php

namespace App\PerformJourney\Application;

use App\Shared\Application\IdDataValidator;
use App\Shared\Application\NotValidIdDataException;

class GroupDataValidator
{
    public function __construct(private IdDataValidator $idDataValidator)
    {
    }

    /**
     * @throws NotValidGroupDataException
     */
    public function validate(mixed $groupData): void
    {
        $this->validateDataIdAndPeopleFields($groupData);
    }

    /**
     * @throws NotValidGroupDataException
     */
    private function validateDataIdAndPeopleFields(mixed $groupData): void
    {
        if (!is_object($groupData)) {
            throw NotValidGroupDataException::create();
        }

        try {
            $this->idDataValidator->validate($groupData);
        } catch (NotValidIdDataException) {
            throw NotValidGroupDataException::create();
        }

        if (!property_exists($groupData, 'people') || !is_int($groupData->people) || !$this->arePeopleInValidRange($groupData->people)) {
            throw NotValidGroupDataException::create();
        }
    }

    private function arePeopleInValidRange(int $people): bool
    {
        return $people >= 1 && $people <= 6;
    }
}
