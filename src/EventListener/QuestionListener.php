<?php

namespace Sherlockode\SyliusFAQPlugin\EventListener;

use Doctrine\ORM\Event\PrePersistEventArgs;
use Doctrine\Persistence\Event\LifecycleEventArgs;
use Sherlockode\SyliusFAQPlugin\Entity\Question;

class QuestionListener
{
    /**
     * @param LifecycleEventArgs $args
     *
     * @return void
     */
    public function prePersist(PrePersistEventArgs $event)
    {
        $entity = $event->getObject();

        if (!$entity instanceof Question) {
            return;
        }

        $question = $event->getObjectManager()->getRepository(Question::class)->findOneBy(
            ['category' => $entity->getCategory()],
            ['position' => 'DESC']
        );

        if (null === $question) {
            $entity->setPosition(1);

            return;
        }

        $entity->setPosition($question->getPosition() + 1);
    }
}
