<?php

namespace App\LocateGroup\Infrastructure;

use App\LocateGroup\Domain\ElectricVehiclesRepository as ElectricVehiclesRepositoryInterface;
use App\Shared\Domain\ElectricVehicle;
use App\Shared\Domain\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ElectricVehiclesRepository extends ServiceEntityRepository implements ElectricVehiclesRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElectricVehicle::class);
    }

    public function locate(Group $group): ElectricVehicle|null
    {
        $dql = 'SELECT ev FROM App:ElectricVehicle ev WHERE :group MEMBER OF ev.groups';

        return $this->_em->createQuery($dql)->setParameters(['group' => $group])->getOneOrNullResult();
    }
}
