<?php


namespace Sherlockode\SyliusFAQPlugin\Form\Listener;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;

class ResizeFormListener implements EventSubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            FormEvents::SUBMIT => ['onSubmit'],
        ];
    }

    public function onSubmit(FormEvent $event)
    {
        $data = $event->getData();

        foreach ($event->getForm()->all() as $translation) {
            $hasValue = false;
            foreach ($translation->all() as $translationValue) {
                if (null !== $translationValue->getData()) {
                    $hasValue = true;
                    break;
                }
            }

            if ($hasValue === false) {
                $data->removeElement($translation->getData());
            }
        }
    }
}
