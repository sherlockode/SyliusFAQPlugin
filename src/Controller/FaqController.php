<?php

namespace Sherlockode\SyliusFAQPlugin\Controller;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Sherlockode\SyliusFAQPlugin\Entity\Question;
use Sylius\Component\Channel\Context\ChannelContextInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;

class FaqController extends AbstractController
{
    /**
     * @param EntityManagerInterface  $em
     * @param ChannelContextInterface $channelContext
     *
     * @return Response
     */
    public function indexAction(EntityManagerInterface $em, ChannelContextInterface $channelContext): Response
    {
        $categories = $em->getRepository(Category::class)->findByChannel($channelContext->getChannel());
        $questions = empty($categories)
            ? $em->getRepository(Question::class)->findByChannel($channelContext->getChannel())
            : [];

        return $this->render('@SherlockodeSyliusFAQPlugin/front/faq/index.html.twig', [
            'categories' => $categories,
            'questions' => $questions,
        ]);
    }
}
