<?php

namespace App\Shared\Domain;

use App\LoadElectricVehicles\Domain\ElectricVehicleWillBeLoaded;
use App\PerformJourney\Domain\GroupWillBeAssignedToElectricVehicle;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class ElectricVehicle
{
    private Collection $groups;

    public function __construct(private Id $id, private int $seats)
    {
        $this->groups = new ArrayCollection();
    }

    public function id(): Id
    {
        return $this->id;
    }

    public function seats(): int
    {
        return $this->seats;
    }

    public function load(): void
    {
        DomainEventsSubscriber::subscribe(new ElectricVehicleWillBeLoaded($this));
    }

    public function assignGroup(Group $group): void
    {
        $group->assignElectricVehicle($this);
        $this->groups->add($group);

        DomainEventsSubscriber::subscribe(new GroupWillBeAssignedToElectricVehicle($this));
    }

    public function groups(): Collection
    {
        return $this->groups;
    }

    public function serialize(): array
    {
        $serializedGroups = [];
        foreach ($this->groups as $group) {
            $serializedGroups[] = $group->serialize();
        }

        return [
            'id' => (int) ((string) $this->id),
            'seats' => $this->seats,
            'groups' => $serializedGroups,
        ];
    }
}
