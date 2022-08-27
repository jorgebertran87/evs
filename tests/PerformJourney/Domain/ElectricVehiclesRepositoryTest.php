<?php

namespace Tests\PerformJourney\Domain;

use App\PerformJourney\Domain\ElectricVehiclesRepository;
use App\Shared\Domain\DomainEventsSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Tests\Shared\ElectricVehiclesMother;
use Tests\Shared\GroupsMother;
use Tests\Shared\IntegrationTestCase;

class ElectricVehiclesRepositoryTest extends IntegrationTestCase
{
    /** @test */
    public function itShouldGetAllElectricVehicles(): void
    {
        /** @var ElectricVehiclesRepository $electricVehiclesRepository */
        $electricVehiclesRepository = $this->getContainer()->get(ElectricVehiclesRepository::class);

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $electricVehicle1 = ElectricVehiclesMother::random();
        $electricVehicle2 = ElectricVehiclesMother::random();
        $electricVehicle3 = ElectricVehiclesMother::random();

        DomainEventsSubscriber::stop();
        $group = GroupsMother::random();
        $electricVehicle2->assignGroup($group);
        DomainEventsSubscriber::init();

        $em->persist($electricVehicle1);
        $em->persist($electricVehicle2);
        $em->persist($electricVehicle3);
        $em->flush();

        $em->clear();

        $allElectricVehicles = $electricVehiclesRepository->getAll();

        $this->assertCount(3, $allElectricVehicles);
    }

    /** @test */
    public function itShouldSaveAnElectricVehicle(): void
    {
        $electricVehicle = ElectricVehiclesMother::random();

        /** @var ElectricVehiclesRepository $electricVehiclesRepository */
        $electricVehiclesRepository = $this->getContainer()->get(ElectricVehiclesRepository::class);
        $electricVehicles = $electricVehiclesRepository->getAll();
        $this->assertCount(0, $electricVehicles);

        $electricVehiclesRepository->save($electricVehicle);

        $electricVehicles = $electricVehiclesRepository->getAll();
        $this->assertCount(1, $electricVehicles);
    }
}
