<?php

namespace App\LoadElectricVehicles\Infrastructure;

use App\LoadElectricVehicles\Domain\ElectricVehiclesRepository as ElectricVehiclesRepositoryInterface;
use App\Shared\Domain\ElectricVehicle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ElectricVehiclesRepository extends ServiceEntityRepository implements ElectricVehiclesRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ElectricVehicle::class);
    }

    public function unloadAll(): void
    {
        $electricVehicles = $this->findBy([]);
        foreach ($electricVehicles as $electricVehicle) {
            $this->_em->remove($electricVehicle);
        }
        $this->_em->flush();
    }

    public function load(ElectricVehicle $electricVehicle): void
    {
        $this->_em->persist($electricVehicle);
        $this->_em->flush();
    }
}
