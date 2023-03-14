<?php

namespace Sherlockode\SyliusFAQPlugin\Controller\Admin;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Sherlockode\SyliusFAQPlugin\Entity\Question;
use Sherlockode\SyliusFAQPlugin\Manager\ResourceManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ResourceController extends AbstractController
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var ResourceManager
     */
    private $resourceManager;

    /**
     * @param EntityManagerInterface $em
     * @param ResourceManager        $resourceManager
     */
    public function __construct(EntityManagerInterface $em, ResourceManager $resourceManager)
    {
        $this->em = $em;
        $this->resourceManager = $resourceManager;
    }

    /**
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function updateResourcesOrderAction(Request $request): JsonResponse
    {
        $submittedToken = $request->request->get('_csrf_token');
        $isValidToken = $this->isCsrfTokenValid('sherlockode-faq-category-tree', $submittedToken);

        if (false === $isValidToken) {
            return new JsonResponse(status: Response::HTTP_FORBIDDEN);
        }

        try {
            $data =  json_decode($request->request->get('resource_order'));
            $resources = $this->resourceManager->normalizeResourcesOrder($data);

            if (count($resources) === 0) {
                return new JsonResponse(status: Response::HTTP_OK);
            }

            if ($resources[0] instanceof Question) {
                /** @var Question $question */
                foreach ($resources as $key => $question) {
                    $question->setPosition($key + 1);
                }
            } elseif (is_array($resources[0]) && isset($resources[0]['category'])) {
                foreach ($resources as $key => $resource) {
                    /** @var Category $category */
                    $category = $resource['category'];
                    $category->setPosition($key + 1);

                    /** @var Question $question */
                    foreach ($resource['questions'] as $questionKey => $question) {
                        $question->setCategory($category);
                        $question->setPosition($questionKey + 1);
                    }
                }
            }

            $this->em->flush();

            return new JsonResponse(status: Response::HTTP_OK);
        } catch (\Exception $e) {
            return new JsonResponse(status: Response::HTTP_INTERNAL_SERVER_ERROR);
        }
    }
}
