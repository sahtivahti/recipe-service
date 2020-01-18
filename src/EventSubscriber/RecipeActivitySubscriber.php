<?php
namespace App\EventSubscriber;

use App\Entity\Recipe;
use App\Model\Event\RecipeCreatedEvent;
use App\Model\Event\RecipeDeletedEvent;
use App\Model\Event\RecipeUpdatedEvent;
use Doctrine\Common\EventSubscriber;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Doctrine\ORM\Events;
use Symfony\Component\Messenger\Envelope;
use Symfony\Component\Messenger\MessageBusInterface;
use Symfony\Component\Messenger\Stamp\SerializerStamp;

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

        $message = (new Envelope(new RecipeCreatedEvent($recipe)))
            ->with(new SerializerStamp([
                'groups' => 'Details'
            ]));

        $this->messageBus->dispatch($message);
    }

    public function postUpdate(LifecycleEventArgs $args): void
    {
        /** @var Recipe $recipe */
        $recipe = $args->getObject();

        if (!$recipe instanceof Recipe) {
            return;
        }

        $message = (new Envelope(new RecipeUpdatedEvent($recipe)))
            ->with(new SerializerStamp([
                'groups' => 'Details'
            ]));

        $this->messageBus->dispatch($message);
    }

    public function postRemove(LifecycleEventArgs $args): void
    {
        /** @var Recipe $recipe */
        $recipe = $args->getObject();

        if (!$recipe instanceof Recipe) {
            return;
        }

        $message = (new Envelope(new RecipeDeletedEvent($recipe)))
            ->with(new SerializerStamp([
                'groups' => 'Details'
            ]));

        $this->messageBus->dispatch($message);
    }
}
