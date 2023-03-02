<?php

namespace Sherlockode\SyliusFAQPlugin\Twig;

use Twig\Extension\RuntimeExtensionInterface;

class TranslationRuntime implements RuntimeExtensionInterface
{
    /**
     * @param        $resource
     * @param string $local
     *
     * @return bool
     */
    public function hasTranslation($resource, string $local): bool
    {
        if (!method_exists($resource, 'getTranslations')) {
            return false;
        }

        return null !== $resource->getTranslations()->get($local);
    }
}
