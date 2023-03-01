<?php

namespace Sherlockode\SyliusFAQPlugin\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FaqController extends AbstractController
{
    /**
     * @param EntityManagerInterface $em
     *
     * @return Response
     */
    public function indexAction(EntityManagerInterface $em): Response
    {
        $categories = $em->getRepository(Category::class)->findBy(criteria: [], orderBy: ['position' => 'ASC']);

        return $this->render('@SherlockodeSyliusFAQPlugin/front/faq/index.html.twig', [
            'categories' => $categories
        ]);
    }
}
