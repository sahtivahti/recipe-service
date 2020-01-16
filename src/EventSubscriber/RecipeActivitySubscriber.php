<?php
namespace App\EventSubscriber;

use App\Entity\Recipe;
use App\Model\Event\RecipeCreatedEvent;
use App\Model\Event\RecipeDeletedEvent;
use App\Model\Event\RecipeUpdatedEvent;
use Doctrine\Common\EventSubscriber;
use Doctrine\Common\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\MessageBusInterface;

class RecipeActivitySubscriber implements EventSubscriber
{
    private MessageBusInterface $messageBus;

    public function __construct(MessageBusInterface $messageBus)
    {
        $this->messageBus = $messageBus;
    }

    /**
     * @inheritDoc
     */
    public function getSubscribedEvents(): array
    {
        return [
            Events::postPersist,
            Events::postUpdate,
            Events::postRemove
        ];
    }

    public function postPersist(LifecycleEventArgs $args): void
    {
        /** @var Recipe $recipe */
        $recipe = $args->getObject();

        if (!$recipe instanceof Recipe) {
            return;
        }

        $this->messageBus->dispatch(new RecipeCreatedEvent($recipe));
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        /** @var Recipe $recipe */
        $recipe = $args->getObject();

        if (!$recipe instanceof Recipe) {
            return;
        }

        $this->messageBus->dispatch(new RecipeUpdatedEvent($recipe));
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        /** @var Recipe $recipe */
        $recipe = $args->getObject();

        if (!$recipe instanceof Recipe) {
            return;
        }

        $this->messageBus->dispatch(new RecipeDeletedEvent($recipe));
    }
}
