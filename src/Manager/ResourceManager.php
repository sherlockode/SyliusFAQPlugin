<?php

namespace Sherlockode\SyliusFAQPlugin\Manager;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Sherlockode\SyliusFAQPlugin\Entity\Question;

class ResourceManager
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
     * @param array $resources
     *
     * @return array
     */
    public function normalizeResourcesOrder(array $resources): array
    {
        $categoryRepository = $this->em->getRepository(Category::class);
        $questionRepository = $this->em->getRepository(Question::class);

        $categoryIterator = -1;
        $normalizedResources = [];

        foreach ($resources as $resource) {
            if (false !== strpos($resource, 'category')) {
                $categoryIterator++;
                $normalizedResources[$categoryIterator] = [
                    'category' =>  $categoryRepository->find(str_replace('category_', '', $resource)),
                    'questions' => []
                ];
                continue;
            }

            if (false !== strpos($resource, 'question')) {
                $normalizedResources[$categoryIterator]['questions'][] = $questionRepository->find(str_replace('question_', '', $resource));
            }
        }

        return $normalizedResources;
    }
}
