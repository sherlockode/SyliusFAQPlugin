<?php

namespace Sherlockode\SyliusFAQPlugin\Twig\Extension;

use Sherlockode\SyliusFAQPlugin\Twig\TranslationRuntime;
use Twig\Extension\AbstractExtension;
use Twig\TwigFunction;

class TranslationExtension extends AbstractExtension
{
    /**
     * @return array
     */
    public function getFunctions(): array
    {
        return [
            new TwigFunction('sherlockode_faq_has_translation', [TranslationRuntime::class, 'hasTranslation']),
        ];
    }
}
