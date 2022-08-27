<?php

namespace App\PerformJourney\Infrastructure;

use App\PerformJourney\Domain\GroupsRepository as GroupsRepositoryInterface;
use App\Shared\Domain\Group;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class GroupsRepository extends ServiceEntityRepository implements GroupsRepositoryInterface
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Group::class);
    }

    public function save(Group $group): void
    {
        $this->_em->persist($group);
        $this->_em->flush();
    }
}
