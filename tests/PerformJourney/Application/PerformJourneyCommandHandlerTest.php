<?php

namespace Tests\PerformJourney\Application;

use App\PerformJourney\Application\GroupDataValidator;
use App\PerformJourney\Application\NotValidGroupDataException;
use App\PerformJourney\Application\PerformJourneyCommand;
use App\PerformJourney\Application\PerformJourneyCommandHandler;
use App\PerformJourney\Domain\ElectricVehiclesRepository;
use App\PerformJourney\Domain\GroupsRepository;
use App\PerformJourney\Domain\GroupWillBeAssignedToElectricVehicleHandler;
use App\PerformJourney\Domain\GroupWillBeRegisteredHandler;
use App\PerformJourney\Domain\JourneyWillBePerformedHandler;
use App\Shared\Application\IdDataValidator;
use App\Shared\Domain\DependenciesInjector;
use App\Shared\Domain\ElectricVehicle;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Shared\ElectricVehiclesMother;

class PerformJourneyCommandHandlerTest extends TestCase
{
    private DependenciesInjector|MockObject $dependenciesInjector;
    private MockObject|ElectricVehiclesRepository $electricVehiclesRepository;
    private ElectricVehicle $electricVehicleWithFourSeats;
    private ElectricVehicle $electricVehicleWithTwoSeats;
    private ElectricVehicle $electricVehicleWithThreeSeats;
    private PerformJourneyCommandHandler $performJourneyCommandHandler;
    private MockObject|GroupsRepository $groupsRepository;

    /** @test */
    public function itShouldNotPerformAJourneyDueToNotLoadedElectricVehicles(): void
    {
        $this->prepareDependencies();

        $journeyWillBePerformedHandler = new JourneyWillBePerformedHandler($this->electricVehiclesRepository);
        $groupWillBeRegisteredHandler = new GroupWillBeRegisteredHandler($this->groupsRepository);
        $this->dependenciesInjector->expects($this->exactly(2))->method('inject')->willReturn($journeyWillBePerformedHandler, $groupWillBeRegisteredHandler);

        $groupsData = '{"id": 1, "people": 3}';
        $performJourneyCommand = new PerformJourneyCommand(json_decode($groupsData));
        $this->performJourneyCommandHandler->handle($performJourneyCommand);
    }

    /** @test */
    public function itShouldNotPerformAJourneyDueToTooManyPeople(): void
    {
        $this->prepareDependenciesWithElectricVehiclesInRandomOrder();

        $journeyWillBePerformedHandler = new JourneyWillBePerformedHandler($this->electricVehiclesRepository);
        $groupWillBeRegisteredHandler = new GroupWillBeRegisteredHandler($this->groupsRepository);
        $this->dependenciesInjector->expects($this->exactly(2))->method('inject')->willReturn($journeyWillBePerformedHandler, $groupWillBeRegisteredHandler);

        $groupsData = '{"id": 1, "people": 5}';
        $performJourneyCommand = new PerformJourneyCommand(json_decode($groupsData));
        $this->performJourneyCommandHandler->handle($performJourneyCommand);
    }

    /** @test */
    public function itShouldPerformAJourneyOnTheElectricVehicleWithFourSeats(): void
    {
        $this->prepareDependenciesWithElectricVehiclesInRandomOrder();

        $journeyWillBePerformedHandler = new JourneyWillBePerformedHandler($this->electricVehiclesRepository);
        $groupWillBeRegisteredHandler = new GroupWillBeRegisteredHandler($this->groupsRepository);
        $groupWillBeAssignedToElectricVehicleHandler = new GroupWillBeAssignedToElectricVehicleHandler($this->electricVehiclesRepository);
        $this->dependenciesInjector->expects($this->exactly(3))->method('inject')->willReturnOnConsecutiveCalls($journeyWillBePerformedHandler, $groupWillBeRegisteredHandler, $groupWillBeAssignedToElectricVehicleHandler);

        $groupsData = '{"id": 1, "people": 4}';
        $performJourneyCommand = new PerformJourneyCommand(json_decode($groupsData));
        $this->performJourneyCommandHandler->handle($performJourneyCommand);

        $this->assertNull($this->electricVehicleWithTwoSeats->groups()[0]);
        $this->assertNull($this->electricVehicleWithThreeSeats->groups()[0]);
        $this->assertEquals(1, (string) $this->electricVehicleWithFourSeats->groups()[0]->id());
        $this->assertEquals(4, (string) $this->electricVehicleWithFourSeats->groups()[0]->people());
    }

    /** @test */
    public function itShouldThrowNotValidGroupDataDueToNotObjectData(): void
    {
        $this->prepareDependencies();

        $groupsData = '[]';

        $performJourneyCommand = new PerformJourneyCommand(json_decode($groupsData));

        $this->expectException(NotValidGroupDataException::class);
        $this->performJourneyCommandHandler->handle($performJourneyCommand);
    }

    /** @test */
    public function itShouldThrowNotValidGroupDataDueToEmptyData(): void
    {
        $this->prepareDependencies();

        $groupsData = '{}';

        $performJourneyCommand = new PerformJourneyCommand(json_decode($groupsData));

        $this->expectException(NotValidGroupDataException::class);
        $this->performJourneyCommandHandler->handle($performJourneyCommand);
    }

    /** @test */
    public function itShouldThrowNotValidGroupDataDueToNotIdData(): void
    {
        $this->prepareDependencies();

        $groupsData = '{"people": 3}';

        $performJourneyCommand = new PerformJourneyCommand(json_decode($groupsData));

        $this->expectException(NotValidGroupDataException::class);
        $this->performJourneyCommandHandler->handle($performJourneyCommand);
    }

    /** @test */
    public function itShouldThrowNotValidGroupDataDueToNotPeopleData(): void
    {
        $this->prepareDependencies();

        $groupsData = '{"id": 3}';

        $performJourneyCommand = new PerformJourneyCommand(json_decode($groupsData));

        $this->expectException(NotValidGroupDataException::class);
        $this->performJourneyCommandHandler->handle($performJourneyCommand);
    }

    private function prepareDependencies(): void
    {
        $this->dependenciesInjector = $this->getMockForAbstractClass(DependenciesInjector::class);
        $this->electricVehiclesRepository = $this->getMockForAbstractClass(ElectricVehiclesRepository::class);
        $this->groupsRepository = $this->getMockForAbstractClass(GroupsRepository::class);

        $idDataValidator = new IdDataValidator();
        $groupsDataValidator = new GroupDataValidator($idDataValidator);
        $this->performJourneyCommandHandler = new PerformJourneyCommandHandler($this->dependenciesInjector, $groupsDataValidator);
    }

    private function prepareDependenciesWithElectricVehiclesInRandomOrder(): void
    {
        $this->prepareDependencies();

        $this->electricVehicleWithFourSeats = ElectricVehiclesMother::createWithSeats(4);
        $this->electricVehicleWithTwoSeats = ElectricVehiclesMother::createWithSeats(2);
        $this->electricVehicleWithThreeSeats = ElectricVehiclesMother::createWithSeats(3);

        $electricVehicles = [$this->electricVehicleWithThreeSeats, $this->electricVehicleWithTwoSeats, $this->electricVehicleWithFourSeats];
        shuffle($electricVehicles);

        $this->electricVehiclesRepository->method('getAll')->willReturn($electricVehicles);
    }
}
