<?php

namespace App\Shared\Domain;

use App\DropOffGroup\Domain\GroupWillBeDroppedOff;
use App\LocateGroup\Domain\GroupWillBeLocated;
use App\PerformJourney\Domain\GroupWillBeRegistered;
use App\PerformJourney\Domain\JourneyWillBePerformed;

class Group
{
    private ElectricVehicle|null $electricVehicle = null;

    public function __construct(private Id $id, private int $people)
    {
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function people(): int
    {
        return $this->people;
    }

    public function performJourney(): void
    {
        DomainEventsSubscriber::subscribe(new JourneyWillBePerformed($this));
    }

    public function dropOff(): void
    {
        DomainEventsSubscriber::subscribe(new GroupWillBeDroppedOff($this));
    }

    public function locate(): void
    {
        DomainEventsSubscriber::subscribe(new GroupWillBeLocated($this));
    }

    public function electricVehicle(): ElectricVehicle|null
    {
        return $this->electricVehicle;
    }

    public function assignElectricVehicle(ElectricVehicle $electricVehicle): void
    {
        $this->electricVehicle = $electricVehicle;
    }

    public function register(): void
    {
        DomainEventsSubscriber::subscribe(new GroupWillBeRegistered($this));
    }

    public function serialize(): array
    {
        return [
            'id' => (int) ((string) $this->id),
            'people' => $this->people,
        ];
    }
}
