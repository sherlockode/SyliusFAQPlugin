<?php

namespace Sherlockode\SyliusFAQPlugin\Menu;

use Sylius\Bundle\UiBundle\Menu\Event\MenuBuilderEvent;

class AdminMenuListener
{
    /**
     * @param MenuBuilderEvent $event
     */
    public function addAdminMenuItems(MenuBuilderEvent $event): void
    {
        $menu = $event->getMenu();
        $configurationItem = $menu->getChild('configuration');

        $configurationItem
            ->addChild('sherlockode_faq', [
                'route' => 'sherlockode_sylius_faq_admin_category_index',
                'extras' => [
                    'routes' => [
                        'sherlockode_sylius_faq_admin_category_create',
                        'sherlockode_sylius_faq_admin_category_update',
                        'sherlockode_sylius_faq_admin_question_create',
                        'sherlockode_sylius_faq_admin_question_update',
                    ]
                ]
            ])
            ->setLabel('sherlockode_sylius_faq.ui.title')
            ->setLabelAttribute('icon', 'question')
        ;
    }
}
