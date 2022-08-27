<?php

namespace Tests\DropOffGroup\Domain;

use App\DropOffGroup\Domain\GroupsRepository;
use Doctrine\ORM\EntityManagerInterface;
use Tests\Shared\GroupsMother;
use Tests\Shared\IntegrationTestCase;

class GroupsRepositoryTest extends IntegrationTestCase
{
    /** @test */
    public function itShouldFindAndDropAGroupOff(): void
    {
        /** @var GroupsRepository $groupsRepository */
        $groupsRepository = $this->getContainer()->get(GroupsRepository::class);

        /** @var EntityManagerInterface $em */
        $em = $this->getContainer()->get('doctrine.orm.entity_manager');

        $group = GroupsMother::random();
        $em->persist($group);
        $em->flush();

        $em->clear();

        $groupFromRepo = $groupsRepository->findOneById($group->id());

        $this->assertEquals($group, $groupFromRepo);

        $groupsRepository->dropOff($groupFromRepo);

        $groupFromRepo = $groupsRepository->findOneById($group->id());
        $this->assertNull($groupFromRepo);
    }
}
