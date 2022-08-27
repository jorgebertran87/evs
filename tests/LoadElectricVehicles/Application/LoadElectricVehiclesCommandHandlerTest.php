<?php

namespace Tests\LoadElectricVehicles\Application;

use App\LoadElectricVehicles\Application\ElectricVehiclesDataValidator;
use App\LoadElectricVehicles\Application\LoadElectricVehiclesCommand;
use App\LoadElectricVehicles\Application\LoadElectricVehiclesCommandHandler;
use App\LoadElectricVehicles\Application\NotValidElectricVehiclesDataException;
use App\LoadElectricVehicles\Domain\ElectricVehiclesRepository;
use App\LoadElectricVehicles\Domain\ElectricVehicleWillBeLoadedHandler;
use App\Shared\Application\IdDataValidator;
use App\Shared\Domain\DependenciesInjector;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class LoadElectricVehiclesCommandHandlerTest extends TestCase
{
    private ElectricVehiclesRepository|MockObject $electricVehiclesRepository;
    private DependenciesInjector|MockObject $dependenciesInjector;
    private ElectricVehiclesDataValidator $electricVehiclesDataValidator;
    private LoadElectricVehiclesCommandHandler $loadElectricVehiclesCommandHandler;

    /** @test */
    public function itShouldLoadTwoElectricVehicles(): void
    {
        $this->prepareDependencies();

        $electricVehiclesData = '[{"id": 1, "seats": 4}, {"id": 2, "seats": 5}]';

        $loadElectricVehiclesCommand = new LoadElectricVehiclesCommand(json_decode($electricVehiclesData));

        $this->electricVehiclesRepository->expects($this->exactly(2))->method('load');
        $this->loadElectricVehiclesCommandHandler->handle($loadElectricVehiclesCommand);
    }

    /** @test */
    public function itShouldThrowNotValidElectricVehiclesDataDueToNotJsonPayload(): void
    {
        $this->prepareDependencies();

        $electricVehiclesData = '[{"id" => 1, "seats": 4}, {"id": 2, "seats": 5}]';

        $loadElectricVehiclesCommand = new LoadElectricVehiclesCommand(json_decode($electricVehiclesData));

        $this->expectException(NotValidElectricVehiclesDataException::class);
        $this->loadElectricVehiclesCommandHandler->handle($loadElectricVehiclesCommand);
    }

    /** @test */
    public function itShouldThrowNotValidElectricVehiclesDataDueToNotArrayData(): void
    {
        $this->prepareDependencies();

        $electricVehiclesData = '{"id": 2, "seats": 5}';

        $loadElectricVehiclesCommand = new LoadElectricVehiclesCommand(json_decode($electricVehiclesData));

        $this->expectException(NotValidElectricVehiclesDataException::class);
        $this->loadElectricVehiclesCommandHandler->handle($loadElectricVehiclesCommand);
    }

    /** @test */
    public function itShouldNotThrowNotValidElectricVehiclesDataDueToEmptyArrayData(): void
    {
        $this->prepareDependencies();

        $electricVehiclesData = '[]';

        $loadElectricVehiclesCommand = new LoadElectricVehiclesCommand(json_decode($electricVehiclesData));

        $this->expectNotToPerformAssertions();
        $this->loadElectricVehiclesCommandHandler->handle($loadElectricVehiclesCommand);
    }

    /** @test */
    public function itShouldThrowNotValidElectricVehiclesDataDueToNotIdData(): void
    {
        $this->prepareDependencies();

        $electricVehiclesData = '[{"seats": 4}]';

        $loadElectricVehiclesCommand = new LoadElectricVehiclesCommand(json_decode($electricVehiclesData));

        $this->expectException(NotValidElectricVehiclesDataException::class);
        $this->loadElectricVehiclesCommandHandler->handle($loadElectricVehiclesCommand);
    }

    /** @test */
    public function itShouldThrowNotValidElectricVehiclesDataDueToNotSeatsData(): void
    {
        $this->prepareDependencies();

        $electricVehiclesData = '[{"id": 4}]';

        $loadElectricVehiclesCommand = new LoadElectricVehiclesCommand(json_decode($electricVehiclesData));

        $this->expectException(NotValidElectricVehiclesDataException::class);
        $this->loadElectricVehiclesCommandHandler->handle($loadElectricVehiclesCommand);
    }

    private function prepareDependencies(): void
    {
        $this->electricVehiclesRepository = $this->getMockForAbstractClass(ElectricVehiclesRepository::class);
        $electricVehicleWillBeLoadedHandler = new ElectricVehicleWillBeLoadedHandler($this->electricVehiclesRepository);
        $this->dependenciesInjector = $this->getMockForAbstractClass(DependenciesInjector::class);
        $this->dependenciesInjector->method('inject')->willReturn($electricVehicleWillBeLoadedHandler)->with($electricVehicleWillBeLoadedHandler::class);
        $idDataValidator = new IdDataValidator();
        $this->electricVehiclesDataValidator = new ElectricVehiclesDataValidator($idDataValidator);

        $this->loadElectricVehiclesCommandHandler = new LoadElectricVehiclesCommandHandler(
            $this->electricVehiclesRepository,
            $this->dependenciesInjector,
            $this->electricVehiclesDataValidator
        );
    }
}
