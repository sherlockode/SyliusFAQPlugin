<?php

namespace Sherlockode\SyliusFAQPlugin\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Twig\Extension\RuntimeExtensionInterface;

class ResourceRuntime implements RuntimeExtensionInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @param EntityManagerInterface $em
     */
    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @return bool
     */
    public function hasCategory(): bool
    {
        return null !== $this->em->getRepository(Category::class)->findOneBy([]);
    }
}
