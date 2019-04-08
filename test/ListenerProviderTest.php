<?php

declare(strict_types=1);

namespace AntidotTest\Event;

use Antidot\Event\EventInterface;
use Antidot\Event\ListenerInterface;
use Antidot\Event\ListenerProvider;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;

class ListenerProviderTest extends TestCase
{
    private const EVENT_NAME = 'SomeEvent';
    /** @var EventInterface|MockObject */
    private $event;
    /** @var ListenerInterface[]|MockObject[] */
    private $listeners;
    /** @var ListenerProvider */
    private $provider;

    public function testItShouldAddListenerToCollection(): void
    {
        $this->givenAnEvent();
        $this->givenSomeListeners();
        $this->whenListenersAreAddedToProvider();
        $this->thenProviderHaveTheListenersSubscribedToTheEvent();
    }


    private function givenAnEvent(): void
    {
        $this->event = $this->createMock(EventInterface::class);
        $this->event
            ->expects($this->once())
            ->method('name')
            ->willReturn(self::EVENT_NAME);
    }

    private function givenSomeListeners(): void
    {
        $this->listeners = [
            $this->createMock(ListenerInterface::class),
            $this->createMock(ListenerInterface::class),
        ];
    }

    private function whenListenersAreAddedToProvider(): void
    {
        $this->provider = new ListenerProvider();
        foreach ($this->listeners as $listener) {
            $this->provider->addListener(self::EVENT_NAME, $listener);
        }
    }

    private function thenProviderHaveTheListenersSubscribedToTheEvent(): void
    {
        $this->assertCount(2, $this->provider->getListenersForEvent($this->event));
    }
}