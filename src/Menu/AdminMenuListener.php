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

        $contentSubmenu = $menu
            ->addChild('faq_content')
            ->setLabel('sherlockode_sylius_faq.ui.content')
        ;

        $contentSubmenu
            ->addChild('sherlockode_faq_category', ['route' => 'sherlockode_sylius_faq_admin_category_index'])
            ->setLabel('sherlockode_sylius_faq.ui.categories')
            ->setLabelAttribute('icon', 'folder')
        ;

        $contentSubmenu
            ->addChild('sherlockode_faq_question', ['route' => 'sherlockode_sylius_faq_admin_question_index'])
            ->setLabel('sherlockode_sylius_faq.ui.questions')
            ->setLabelAttribute('icon', 'question circle outline')
        ;
    }
}
