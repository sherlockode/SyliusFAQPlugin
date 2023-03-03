<?php

namespace Sherlockode\SyliusFAQPlugin\Twig\Extension;

use Sherlockode\SyliusFAQPlugin\Twig\TreeRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TreeExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('sherlockode_faq_generate_tree', [TreeRuntime::class, 'generateTree']),
        ];
    }
}
