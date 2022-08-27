<?php

namespace App\Shared\Domain;

class DomainEventsSubscriber
{
    private static array $subscribedEvents = [];

    private static bool $isStopped = false;

    public static function subscribe(DomainEvent $domainEvent): void
    {
        if (self::$isStopped) {
            return;
        }

        self::$subscribedEvents[] = $domainEvent;
    }

    public static function handle(DependenciesInjector $dependenciesInjector): array
    {
        $results = [];

        /* @var DomainEvent $subscribedEvent */
        while (count(self::$subscribedEvents) > 0) {
            $subscribedEvent = array_shift(self::$subscribedEvents);
            $subscribedEventHandler = self::handlerFromDomainEvent($subscribedEvent, $dependenciesInjector);
            $results[$subscribedEvent::class] = $subscribedEventHandler->handle($subscribedEvent);
        }

        return $results;
    }

    private static function handlerFromDomainEvent(DomainEvent $domainEvent, DependenciesInjector $dependenciesInjector): DomainEventHandler
    {
        $domainEventHandlerClass = $domainEvent::class.'Handler';
        /** @var DomainEventHandler $domainEventHandler */
        $domainEventHandler = $dependenciesInjector->inject($domainEventHandlerClass);

        return $domainEventHandler;
    }

    public static function stop(): void
    {
        self::$isStopped = true;
    }

    public static function init(): void
    {
        self::$isStopped = false;
    }
}
