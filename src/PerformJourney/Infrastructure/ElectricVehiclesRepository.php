<?php

namespace App\PerformJourney\Infrastructure;

use App\PerformJourney\Domain\ElectricVehiclesRepository as ElectricVehiclesRepositoryInterface;
use App\Shared\Domain\ElectricVehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ElectricVehiclesRepository extends ServiceEntityRepository implements ElectricVehiclesRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElectricVehicle::class);
    }

    public function getAll(): array
    {
        $dql = 'SELECT ev FROM App:ElectricVehicle ev';

        return $this->_em->createQuery($dql)->getResult();
    }

    public function save(ElectricVehicle $electricVehicle): void
    {
        $this->_em->persist($electricVehicle);
        $this->_em->flush();
    }
}
