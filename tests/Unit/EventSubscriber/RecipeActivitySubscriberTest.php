<?php
namespace App\Tests\Unit\EventSubscriber;

use App\Entity\Hop;
use App\Entity\Recipe;
use App\EventSubscriber\RecipeActivitySubscriber;
use App\Model\Event\RecipeCreatedEvent;
use App\Model\Event\RecipeDeletedEvent;
use App\Model\Event\RecipeUpdatedEvent;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\Persistence\ObjectManager;
use PHPUnit\Framework\MockObject\MockObject;
use PHPUnit\Framework\TestCase;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;

class RecipeActivitySubscriberTest extends TestCase
{
    /**
     * @var MessageBusInterface|MockObject
     */
    private $messageBusMock;

    public function setUp(): void
    {
        $this->messageBusMock = $this->createMock(MessageBusInterface::class);
    }

    public function testThatPostPersistDispatchRecipeCreatedEventIfEntityIsRecipe(): void
    {
        $recipe = new Recipe();

        $this->messageBusMock
            ->expects(static::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(RecipeCreatedEvent::class))
            ->willReturn(new Envelope($recipe));

        $subscriber = new RecipeActivitySubscriber($this->messageBusMock);
        $objectManager = $this->createMock(ObjectManager::class);
        $event = new LifecycleEventArgs($recipe, $objectManager);

        $subscriber->postPersist($event);
    }

    public function testThatPostPersistDoesNothingIfEntityIsNotRecipe(): void
    {
        $hop = new Hop();

        $this->messageBusMock
            ->expects(static::never())
            ->method('dispatch')
            ->willReturn(new Envelope($hop));

        $subscriber = new RecipeActivitySubscriber($this->messageBusMock);
        $objectManager = $this->createMock(ObjectManager::class);
        $event = new LifecycleEventArgs($hop, $objectManager);

        $subscriber->postPersist($event);
    }

    public function testThatPostUpdateDispatchRecipeUpdatedEventIfEntityIsRecipe(): void
    {
        $recipe = new Recipe();

        $this->messageBusMock
            ->expects(static::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(RecipeUpdatedEvent::class))
            ->willReturn(new Envelope($recipe));

        $subscriber = new RecipeActivitySubscriber($this->messageBusMock);
        $objectManager = $this->createMock(ObjectManager::class);
        $event = new LifecycleEventArgs($recipe, $objectManager);

        $subscriber->postUpdate($event);
    }

    public function testThatPostUpdateDoesNothingIfEntityIsNotRecipe(): void
    {
        $hop = new Hop();

        $this->messageBusMock
            ->expects(static::never())
            ->method('dispatch')
            ->willReturn(new Envelope($hop));

        $subscriber = new RecipeActivitySubscriber($this->messageBusMock);
        $objectManager = $this->createMock(ObjectManager::class);
        $event = new LifecycleEventArgs($hop, $objectManager);

        $subscriber->postUpdate($event);
    }

    public function testThatPostRemoveDispatchRecipeDeletedEventIfEntityIsRecipe(): void
    {
        $recipe = new Recipe();

        $this->messageBusMock
            ->expects(static::once())
            ->method('dispatch')
            ->with(self::isInstanceOf(RecipeDeletedEvent::class))
            ->willReturn(new Envelope($recipe));

        $subscriber = new RecipeActivitySubscriber($this->messageBusMock);
        $objectManager = $this->createMock(ObjectManager::class);
        $event = new LifecycleEventArgs($recipe, $objectManager);

        $subscriber->postRemove($event);
    }

    public function testThatPostRemoveDoesNothingIfEntityIsNotRecipe(): void
    {
        $hop = new Hop();

        $this->messageBusMock
            ->expects(static::never())
            ->method('dispatch')
            ->willReturn(new Envelope($hop));

        $subscriber = new RecipeActivitySubscriber($this->messageBusMock);
        $objectManager = $this->createMock(ObjectManager::class);
        $event = new LifecycleEventArgs($hop, $objectManager);

        $subscriber->postRemove($event);
    }
}
