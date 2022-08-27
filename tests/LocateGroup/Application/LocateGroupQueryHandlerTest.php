<?php

namespace Tests\LocateGroup\Application;

use App\LocateGroup\Application\LocateGroupQuery;
use App\LocateGroup\Application\LocateGroupQueryHandler;
use App\LocateGroup\Domain\ElectricVehiclesRepository;
use App\LocateGroup\Domain\GroupIsWaitingToBeAssignedException;
use App\LocateGroup\Domain\GroupNotFoundException;
use App\LocateGroup\Domain\GroupsRepository;
use App\LocateGroup\Domain\GroupWillBeLocatedHandler;
use App\Shared\Application\IdDataValidator;
use App\Shared\Application\NotValidIdDataException;
use App\Shared\Domain\DependenciesInjector;
use App\Shared\Domain\ElectricVehicle;
use App\Shared\Domain\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Shared\ElectricVehiclesMother;
use Tests\Shared\GroupsMother;

class LocateGroupQueryHandlerTest extends TestCase
{
    private DependenciesInjector|MockObject $dependenciesInjector;
    private GroupsRepository|MockObject $groupsRepository;
    private Group $groupFound;
    private ElectricVehiclesRepository|MockObject $electricVehiclesRepository;
    private ElectricVehicle $electricVehicle;
    private LocateGroupQueryHandler $locateGroupQueryHandler;

    /** @test */
    public function itShouldThrowExceptionDueToGroupNotFound(): void
    {
        $this->prepareDependencies();

        $idData = '{"id": 1}';
        $locateGroupQuery = new LocateGroupQuery(json_decode($idData));

        $this->expectException(GroupNotFoundException::class);
        $this->locateGroupQueryHandler->handle($locateGroupQuery);
    }

    /** @test */
    public function itShouldThrowExceptionDueToGroupNotAssigned(): void
    {
        $this->mockDependenciesWithGroupFound();

        $idData = '{"id": 1}';
        $locateGroupQuery = new LocateGroupQuery(json_decode($idData));

        $groupWillBeLocatedHandler = new GroupWillBeLocatedHandler($this->electricVehiclesRepository);
        $this->dependenciesInjector->expects($this->once())->method('inject')->willReturn($groupWillBeLocatedHandler);
        $this->expectException(GroupIsWaitingToBeAssignedException::class);
        $this->locateGroupQueryHandler->handle($locateGroupQuery);
    }

    /** @test */
    public function itShouldLocateAGroup(): void
    {
        $this->mockDependenciesWithGroupLocated();

        $idData = '{"id": 1}';
        $locateGroupQuery = new LocateGroupQuery(json_decode($idData));

        $groupWillBeLocatedHandler = new GroupWillBeLocatedHandler($this->electricVehiclesRepository);
        $this->dependenciesInjector->expects($this->once())->method('inject')->willReturn($groupWillBeLocatedHandler);

        $electricVehicle = $this->locateGroupQueryHandler->handle($locateGroupQuery);

        $this->assertEquals($electricVehicle['id'], (string) $this->electricVehicle->id());
    }

    /** @test */
    public function itShouldThrowNotValidIdDataDueToNotObjectData(): void
    {
        $this->prepareDependencies();

        $groupsData = '[]';

        $locateGroupQuery = new LocateGroupQuery(json_decode($groupsData));

        $this->expectException(NotValidIdDataException::class);
        $this->locateGroupQueryHandler->handle($locateGroupQuery);
    }

    /** @test */
    public function itShouldThrowNotValidIdDataDueToEmptyData(): void
    {
        $this->prepareDependencies();

        $groupsData = '{}';

        $locateGroupQuery = new LocateGroupQuery(json_decode($groupsData));

        $this->expectException(NotValidIdDataException::class);
        $this->locateGroupQueryHandler->handle($locateGroupQuery);
    }

    /** @test */
    public function itShouldThrowNotValidIdDataDueToNotIdData(): void
    {
        $this->prepareDependencies();

        $groupsData = '{"test": 1}';

        $locateGroupQuery = new LocateGroupQuery(json_decode($groupsData));

        $this->expectException(NotValidIdDataException::class);
        $this->locateGroupQueryHandler->handle($locateGroupQuery);
    }

    private function prepareDependencies(): void
    {
        $this->groupsRepository = $this->getMockForAbstractClass(GroupsRepository::class);
        $this->dependenciesInjector = $this->getMockForAbstractClass(DependenciesInjector::class);

        $idDataValidator = new IdDataValidator();
        $this->locateGroupQueryHandler = new LocateGroupQueryHandler(
            $this->groupsRepository,
            $this->dependenciesInjector,
            $idDataValidator
        );
    }

    private function mockDependenciesWithGroupFound(): void
    {
        $this->prepareDependencies();

        $this->electricVehiclesRepository = $this->getMockForAbstractClass(ElectricVehiclesRepository::class);

        $this->groupFound = GroupsMother::random();
        $this->groupsRepository->method('findOneById')->willReturn($this->groupFound);
    }

    private function mockDependenciesWithGroupLocated(): void
    {
        $this->mockDependenciesWithGroupFound();

        $this->electricVehicle = ElectricVehiclesMother::random();
        $this->electricVehiclesRepository->method('locate')->willReturn($this->electricVehicle);
    }
}
