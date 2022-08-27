<?php

namespace App\LoadElectricVehicles\Application;

use App\LoadElectricVehicles\Domain\ElectricVehiclesRepository;
use App\Shared\Domain\DependenciesInjector;
use App\Shared\Domain\DomainEventsSubscriber;
use App\Shared\Domain\ElectricVehicle;
use App\Shared\Domain\Id;

class LoadElectricVehiclesCommandHandler
{
    public function __construct(
        private ElectricVehiclesRepository $electricVehiclesRepository,
        private DependenciesInjector $dependenciesInjector,
        private ElectricVehiclesDataValidator $electricVehicleDataValidator
    ) {
    }

    /**
     * @throws NotValidElectricVehiclesDataException
     */
    public function handle(LoadElectricVehiclesCommand $loadElectricVehiclesCommand): array
    {
        $this->electricVehicleDataValidator->validate($loadElectricVehiclesCommand->electricVehiclesData());
        $this->electricVehiclesRepository->unloadAll();

        foreach ($loadElectricVehiclesCommand->electricVehiclesData() as $electricVehicleData) {
            $electricVehicle = new ElectricVehicle(new Id($electricVehicleData->id), $electricVehicleData->seats);
            $electricVehicle->load();
        }

        /** @var ElectricVehicle[] $electricVehicles */
        $electricVehicles = DomainEventsSubscriber::handle($this->dependenciesInjector);

        $response = [];
        foreach ($electricVehicles as $electricVehicle) {
            $response[] = $electricVehicle->serialize();
        }

        return $response;
    }
}
