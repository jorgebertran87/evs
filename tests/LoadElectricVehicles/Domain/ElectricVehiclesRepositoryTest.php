<?php

namespace Tests\LoadElectricVehicles\Domain;

use App\LoadElectricVehicles\Domain\ElectricVehiclesRepository;
use Doctrine\ORM\EntityManagerInterface;
use Tests\Shared\ElectricVehiclesMother;
use Tests\Shared\IntegrationTestCase;

class ElectricVehiclesRepositoryTest extends IntegrationTestCase
{
    /** @test */
    public function itShouldLoadAndUnloadAllTheElectricVehicles(): void
    {
        /** @var ElectricVehiclesRepository $electricVehiclesRepository */
        $electricVehiclesRepository = $this->getContainer()->get(ElectricVehiclesRepository::class);

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $electricVehicle = ElectricVehiclesMother::random();
        $electricVehiclesRepository->load($electricVehicle);

        $electricVehicleFromRepo = $em->find($electricVehicle::class, (string) $electricVehicle->id());

        $this->assertEquals($electricVehicle, $electricVehicleFromRepo);

        $electricVehiclesRepository->unloadAll();

        $em->clear();

        $electricVehicleFromRepo = $em->find($electricVehicle::class, (string) $electricVehicle->id());
        $this->assertNull($electricVehicleFromRepo);
    }
}
