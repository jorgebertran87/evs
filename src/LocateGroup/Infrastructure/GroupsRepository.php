<?php

namespace App\LocateGroup\Infrastructure;

use App\LocateGroup\Domain\GroupsRepository as GroupsRepositoryInterface;
use App\Shared\Domain\Group;
use App\Shared\Domain\Id;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GroupsRepository extends ServiceEntityRepository implements GroupsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function findOneById(Id $id): Group|null
    {
        return $this->findOneBy(['id.identifier' => (string) $id]);
    }
}
