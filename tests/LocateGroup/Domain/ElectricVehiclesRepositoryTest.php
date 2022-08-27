<?php

namespace Tests\LocateGroup\Domain;

use App\LocateGroup\Domain\ElectricVehiclesRepository;
use App\Shared\Domain\DomainEventsSubscriber;
use Doctrine\ORM\EntityManagerInterface;
use Tests\Shared\ElectricVehiclesMother;
use Tests\Shared\GroupsMother;
use Tests\Shared\IntegrationTestCase;

class ElectricVehiclesRepositoryTest extends IntegrationTestCase
{
    /** @test */
    public function itShouldLocateAGroup(): void
    {
        /** @var ElectricVehiclesRepository $electricVehiclesRepository */
        $electricVehiclesRepository = $this->getContainer()->get(ElectricVehiclesRepository::class);

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $electricVehicle1 = ElectricVehiclesMother::random();
        $electricVehicle2 = ElectricVehiclesMother::random();

        DomainEventsSubscriber::stop();
        $group = GroupsMother::random();
        $electricVehicle2->assignGroup($group);
        DomainEventsSubscriber::init();

        $em->persist($electricVehicle1);
        $em->persist($electricVehicle2);
        $em->flush();

        $em->clear();

        $electricVehicleFromRepo = $electricVehiclesRepository->locate($group);

        $this->assertTrue($electricVehicle2->id()->equals($electricVehicleFromRepo->id()));
        $this->assertEquals($electricVehicle2->seats(), $electricVehicleFromRepo->seats());
        $this->assertTrue($electricVehicle2->groups()[0]->id()->equals($electricVehicleFromRepo->groups()[0]->id()));
    }
}
