<?php

namespace Sherlockode\SyliusFAQPlugin\Twig;

use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class TreeRuntime implements RuntimeExtensionInterface
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @param UrlGeneratorInterface $urlGenerator
     * @param Environment           $twig
     */
    public function __construct(UrlGeneratorInterface $urlGenerator, Environment $twig)
    {
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
    }

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
                'level' => 1,
                'min_level' => 1,
                'max_level' => 1,
                'edit_path' => $this->urlGenerator->generate('sherlockode_sylius_faq_admin_category_update', ['id' => $category->getId()]),
                'delete_form' => $this->twig->render('@SherlockodeSyliusFAQPlugin/admin/Grid/Action/delete.html.twig', [
                    'path' => $this->urlGenerator->generate('sherlockode_sylius_faq_admin_category_delete', ['id' => $category->getId()]),
                    'id' => $category->getId(),
                ])
            ];

            foreach ($category->getQuestions() as $question) {
                $id++;

                $tree[$id] = [
                    'id' => 'question_' . $question->getId(),
                    'title' => $question->getQuestion(),
                    'parent_id' => 'category_' . $category->getId(),
                    'level' => 2,
                    'min_level' => 2,
                    'max_level' => 2,
                    'edit_path' => $this->urlGenerator->generate('sherlockode_sylius_faq_admin_question_update', ['id' => $question->getId()]),
                    'delete_form' => $this->twig->render('@SherlockodeSyliusFAQPlugin/admin/Grid/Action/delete.html.twig', [
                        'path' => $this->urlGenerator->generate('sherlockode_sylius_faq_admin_question_delete', ['id' => $question->getId()]),
                        'id' => $question->getId(),
                    ])
                ];
            }

            $id++;
        }

        return json_encode($tree);
    }
}
