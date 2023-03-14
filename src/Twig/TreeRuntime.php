<?php

namespace Sherlockode\SyliusFAQPlugin\Twig;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Sherlockode\SyliusFAQPlugin\Entity\CategoryTranslation;
use Sherlockode\SyliusFAQPlugin\Entity\Question;
use Sherlockode\SyliusFAQPlugin\Entity\QuestionTranslation;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\Environment;
use Twig\Extension\RuntimeExtensionInterface;

class TreeRuntime implements RuntimeExtensionInterface
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    /**
     * @var Environment
     */
    private $twig;

    /**
     * @param EntityManagerInterface $em
     * @param UrlGeneratorInterface  $urlGenerator
     * @param Environment            $twig
     */
    public function __construct(EntityManagerInterface $em, UrlGeneratorInterface $urlGenerator, Environment $twig)
    {
        $this->em = $em;
        $this->urlGenerator = $urlGenerator;
        $this->twig = $twig;
    }

    /**
     * @return string
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function generateTree(): string
    {
        $tree = [];

        $categories = $this->em->getRepository(Category::class)->findBy([], ['position' => 'ASC']);
        if (!empty($categories)) {
            $tree = $this->generateCategoriesTree($categories);
        } else {
            $questions = $this->em->getRepository(Question::class)->findBy(['category' => null], ['position' => 'ASC']);
            $tree = $this->generateQuestionsTree($questions);
        }

        return json_encode($tree);
    }

    /**
     * @param iterable $translations
     *
     * @return array
     */
    private function getLocaleCodes(iterable $translations): array
    {
        $codes = [];

        foreach ($translations as $translation) {
            if ($translation instanceof CategoryTranslation && null === $translation->getName()) {
                continue;
            }

            if ($translation instanceof QuestionTranslation && null === $translation->getQuestion()) {
                continue;
            }

            $codes[] = strtolower(substr(strrchr($translation->getLocale(), '_'), 1));
        }

        return $codes;
    }

    /**
     * @param $resource
     *
     * @return string|null
     */
    private function getDefaultLabel($resource): ?string
    {
        $label = null;

        if ($resource instanceof Category) {
            $label = $resource->getName();

            if (null === $label) {
                $translation = $resource->getTranslations()->first();
                if (false !== $translation) {
                    $label = $translation->getName();
                }
            }
        }

        if ($resource instanceof Question) {
            $label = $resource->getQuestion();

            if (null === $label) {
                $translation = $resource->getTranslations()->first();
                if (false !== $translation) {
                    $label = $translation->getQuestion();
                }
            }
        }

        return $label;
    }

    /**
     * @param Category[] $categories
     *
     * @return array
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function generateCategoriesTree(array $categories): array
    {
        $id = 0;
        $tree = [];

        foreach ($categories as $category) {
            $tree[$id] = [
                'id' => 'category_' . $category->getId(),
                'title' => $this->getDefaultLabel($category),
                'parent_id' => 0,
                'level' => 1,
                'min_level' => 1,
                'max_level' => 1,
                'type' => 'category',
                'nb_question' => $category->getQuestions()->count(),
                'locales' => $this->getLocaleCodes($category->getTranslations()),
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
                    'title' => $this->getDefaultLabel($question),
                    'parent_id' => 'category_' . $category->getId(),
                    'level' => 2,
                    'min_level' => 2,
                    'max_level' => 2,
                    'type' => 'question',
                    'locales' => $this->getLocaleCodes($question->getTranslations()),
                    'edit_path' => $this->urlGenerator->generate('sherlockode_sylius_faq_admin_question_update', ['id' => $question->getId()]),
                    'delete_form' => $this->twig->render('@SherlockodeSyliusFAQPlugin/admin/Grid/Action/delete.html.twig', [
                        'path' => $this->urlGenerator->generate('sherlockode_sylius_faq_admin_question_delete', ['id' => $question->getId()]),
                        'id' => $question->getId(),
                    ])
                ];
            }

            $id++;
        }

        return $tree;
    }

    /**
     * @param Question[] $questions
     *
     * @return array
     *
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    private function generateQuestionsTree(array $questions): array
    {
        $tree = [];

        foreach ($questions as $id => $question) {
            $tree[$id] = [
                'id' => 'question_' . $question->getId(),
                'title' => $this->getDefaultLabel($question),
                'parent_id' => 0,
                'level' => 1,
                'min_level' => 1,
                'max_level' => 1,
                'nb_question' => 0,
                'type' => 'question',
                'locales' => $this->getLocaleCodes($question->getTranslations()),
                'edit_path' => $this->urlGenerator->generate('sherlockode_sylius_faq_admin_question_update', ['id' => $question->getId()]),
                'delete_form' => $this->twig->render('@SherlockodeSyliusFAQPlugin/admin/Grid/Action/delete.html.twig', [
                    'path' => $this->urlGenerator->generate('sherlockode_sylius_faq_admin_question_delete', ['id' => $question->getId()]),
                    'id' => $question->getId(),
                ])
            ];
        }

        return $tree;
    }
}
