<?php

namespace App\Shared\Application;

use App\Shared\Domain\DependenciesInjector;

class MessageBus
{
    public function __construct(private DependenciesInjector $dependenciesInjector)
    {
    }

    public function handle(Command|Query $message): mixed
    {
        $messageClassHandler = $message::class.'Handler';
        $messageHandler = $this->dependenciesInjector->inject($messageClassHandler);

        return $messageHandler->handle($message);
    }
}
