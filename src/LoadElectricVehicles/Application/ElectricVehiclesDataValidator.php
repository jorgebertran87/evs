<?php

namespace App\LoadElectricVehicles\Application;

use App\Shared\Application\IdDataValidator;
use App\Shared\Application\NotValidIdDataException;

class ElectricVehiclesDataValidator
{
    public function __construct(private IdDataValidator $idDataValidator)
    {
    }

    /**
     * @throws NotValidElectricVehiclesDataException
     */
    public function validate(mixed $electricVehiclesData): void
    {
        if (!is_array($electricVehiclesData)) {
            throw NotValidElectricVehiclesDataException::create();
        }

        $this->validateDataIdAndSeatsFields($electricVehiclesData);
    }

    /**
     * @throws NotValidElectricVehiclesDataException
     */
    private function validateDataIdAndSeatsFields(array $electricVehiclesData): void
    {
        foreach ($electricVehiclesData as $electricVehicleData) {
            if (!is_object($electricVehicleData)) {
                throw NotValidElectricVehiclesDataException::create();
            }

            try {
                $this->idDataValidator->validate($electricVehicleData);
            } catch (NotValidIdDataException) {
                throw NotValidElectricVehiclesDataException::create();
            }

            if (!property_exists($electricVehicleData, 'seats') || !is_int($electricVehicleData->seats) || !$this->areSeatsInValidRange($electricVehicleData->seats)) {
                throw NotValidElectricVehiclesDataException::create();
            }
        }
    }

    private function areSeatsInValidRange(int $seats): bool
    {
        return $seats >= 1 && $seats <= 6;
    }
}
