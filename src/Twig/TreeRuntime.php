<?php

namespace Sherlockode\SyliusFAQPlugin\Twig;

use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Twig\Extension\RuntimeExtensionInterface;

class TreeRuntime implements RuntimeExtensionInterface
{

    /**
     * @param Category[] $categories
     *
     * @return string
     */
    public function generateTree(iterable $categories): string
    {
        $tree = [];

        $id = 0;
        foreach ($categories as $category) {
            $tree[$id] = [
                'id' => 'category_' . $category->getId(),
                'title' => $category->getName(),
                'parent_id' => 0,
                'level' => 1
            ];

            foreach ($category->getQuestions() as $question) {
                $id++;

                $tree[$id] = [
                    'id' => 'question_' . $question->getId(),
                    'title' => $question->getQuestion(),
                    'parent_id' => 'category_' . $category->getId(),
                    'level' => 2
                ];
            }

            $id++;
        }

        return json_encode($tree);
    }
}
