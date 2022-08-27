<?php

namespace App\DropOffGroup\Application;

use App\DropOffGroup\Domain\GroupIsNotLoadedException;
use App\DropOffGroup\Domain\GroupsRepository;
use App\Shared\Application\IdDataValidator;
use App\Shared\Application\NotValidIdDataException;
use App\Shared\Domain\DependenciesInjector;
use App\Shared\Domain\DomainEventsSubscriber;
use App\Shared\Domain\Id;

class DropOffGroupCommandHandler
{
    public function __construct(
        private GroupsRepository $groupsRepository,
        private DependenciesInjector $dependenciesInjector,
        private IdDataValidator $idDataValidator
    ) {
    }

    /**
     * @throws GroupIsNotLoadedException|NotValidIdDataException
     */
    public function handle(DropOffGroupCommand $dropOffGroupCommand): void
    {
        $this->idDataValidator->validate($dropOffGroupCommand->groupData());

        $id = new Id($dropOffGroupCommand->groupData()->id);
        $loadedGroup = $this->groupsRepository->findOneById($id);

        if (is_null($loadedGroup)) {
            throw GroupIsNotLoadedException::createFromId($id);
        }

        $loadedGroup->dropOff();

        DomainEventsSubscriber::handle($this->dependenciesInjector);
    }
}
