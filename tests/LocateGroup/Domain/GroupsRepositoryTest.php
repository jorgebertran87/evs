<?php

namespace Tests\LocateGroup\Domain;

use App\LocateGroup\Domain\GroupsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Tests\Shared\GroupsMother;
use Tests\Shared\IntegrationTestCase;

class GroupsRepositoryTest extends IntegrationTestCase
{
    /** @test */
    public function itShouldFindAGroupById(): void
    {
        /** @var GroupsRepository $groupsRepository */
        $groupsRepository = $this->getContainer()->get(GroupsRepository::class);

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $group1 = GroupsMother::random();
        $group2 = GroupsMother::random();
        $em->persist($group1);
        $em->persist($group2);
        $em->flush();

        $em->clear();

        $groupFromRepo = $groupsRepository->findOneById($group2->id());

        $this->assertTrue($group2->id()->equals($groupFromRepo->id()));
        $this->assertEquals($group2->people(), $groupFromRepo->people());
    }
}
