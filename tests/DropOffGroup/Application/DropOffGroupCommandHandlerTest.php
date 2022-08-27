<?php

namespace Tests\DropOffGroup\Application;

use App\DropOffGroup\Application\DropOffGroupCommand;
use App\DropOffGroup\Application\DropOffGroupCommandHandler;
use App\DropOffGroup\Domain\GroupIsNotLoadedException;
use App\DropOffGroup\Domain\GroupsRepository;
use App\DropOffGroup\Domain\GroupWillBeDroppedOffHandler;
use App\Shared\Application\IdDataValidator;
use App\Shared\Application\NotValidIdDataException;
use App\Shared\Domain\DependenciesInjector;
use App\Shared\Domain\Group;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Tests\Shared\GroupsMother;

class DropOffGroupCommandHandlerTest extends TestCase
{
    private DependenciesInjector|MockObject $dependenciesInjector;
    private GroupsRepository|MockObject $groupsRepository;
    private Group $groupToBeDroppedOff;
    private DropOffGroupCommandHandler $dropOffGroupCommandHandler;

    /** @test */
    public function itShouldThrowExceptionDueToGroupNotLoaded(): void
    {
        $this->prepareDependencies();

        $idData = '{"id": 1}';
        $dropOffGroupCommand = new DropOffGroupCommand(json_decode($idData));

        $this->expectException(GroupIsNotLoadedException::class);
        $this->dropOffGroupCommandHandler->handle($dropOffGroupCommand);
    }

    /** @test */
    public function itShouldDropAGroupOff(): void
    {
        $this->mockDependenciesWithGroupToBeDroppedOff();

        $idData = '{"id": 1}';
        $dropOffGroupCommand = new DropOffGroupCommand(json_decode($idData));

        $groupWillBeDroppedOffHandler = new GroupWillBeDroppedOffHandler($this->groupsRepository);
        $this->dependenciesInjector->expects($this->once())->method('inject')->willReturn($groupWillBeDroppedOffHandler);
        $this->groupsRepository->expects($this->once())->method('dropOff')->with($this->groupToBeDroppedOff);
        $this->dropOffGroupCommandHandler->handle($dropOffGroupCommand);
    }

    /** @test */
    public function itShouldThrowNotValidIdDataDueToNotObjectData(): void
    {
        $this->prepareDependencies();

        $groupsData = '[]';

        $dropOffGroupCommand = new DropOffGroupCommand(json_decode($groupsData));

        $this->expectException(NotValidIdDataException::class);
        $this->dropOffGroupCommandHandler->handle($dropOffGroupCommand);
    }

    /** @test */
    public function itShouldThrowNotValidIdDataDueToEmptyData(): void
    {
        $this->prepareDependencies();

        $groupsData = '{}';

        $dropOffGroupCommand = new DropOffGroupCommand(json_decode($groupsData));

        $this->expectException(NotValidIdDataException::class);
        $this->dropOffGroupCommandHandler->handle($dropOffGroupCommand);
    }

    /** @test */
    public function itShouldThrowNotValidIdDataDueToNotIdData(): void
    {
        $this->prepareDependencies();

        $groupsData = '{"test": 1}';

        $dropOffGroupCommand = new DropOffGroupCommand(json_decode($groupsData));

        $this->expectException(NotValidIdDataException::class);
        $this->dropOffGroupCommandHandler->handle($dropOffGroupCommand);
    }

    private function prepareDependencies(): void
    {
        $this->groupsRepository = $this->getMockForAbstractClass(GroupsRepository::class);
        $this->dependenciesInjector = $this->getMockForAbstractClass(DependenciesInjector::class);

        $idDataValidator = new IdDataValidator();
        $this->dropOffGroupCommandHandler = new DropOffGroupCommandHandler(
            $this->groupsRepository,
            $this->dependenciesInjector,
            $idDataValidator
        );
    }

    private function mockDependenciesWithGroupToBeDroppedOff(): void
    {
        $this->prepareDependencies();

        $this->groupToBeDroppedOff = GroupsMother::random();
        $this->groupsRepository->method('findOneById')->willReturn($this->groupToBeDroppedOff);
    }
}
