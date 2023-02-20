<?php

declare(strict_types=1);

namespace Sherlockode\SyliusFAQPlugin\Action;

use Doctrine\ORM\EntityManagerInterface;
use Sherlockode\SyliusFAQPlugin\Entity\Category;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\HttpException;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Csrf\CsrfToken;
use Symfony\Component\Security\Csrf\CsrfTokenManagerInterface;

class RemoveCategoryIconAction
{
    /**
     * @var EntityManagerInterface
     */
    private $em;

    /**
     * @var RouterInterface
     */
    private $router;

    /**
     * @var CsrfTokenManagerInterface
     */
    private $csrfTokenManager;

    /**
     * @param EntityManagerInterface    $em
     * @param RouterInterface           $router
     * @param CsrfTokenManagerInterface $csrfTokenManager
     */
    public function __construct(
        EntityManagerInterface $em,
        RouterInterface $router,
        CsrfTokenManagerInterface $csrfTokenManager
    ) {
        $this->em = $em;
        $this->router = $router;
        $this->csrfTokenManager = $csrfTokenManager;
    }

    /**
     * @param Request $request
     *
     * @return Response
     */
    public function __invoke(Request $request): Response
    {
        $categoryId = $request->attributes->get('id');

        if (!$this->csrfTokenManager->isTokenValid(new CsrfToken($categoryId, (string) $request->query->get('_csrf_token')))) {
            throw new HttpException(Response::HTTP_FORBIDDEN, 'Invalid csrf token.');
        }

        $categoryRepository = $this->em->getRepository(Category::class);

        /** @var Category|null $category */
        $category = $categoryRepository->find($categoryId);

        if (null !== $category) {
            $category->setIconPath(null);
            $this->em->flush();
        }

        return new RedirectResponse($this->router->generate('sherlockode_sylius_faq_admin_category_update', [
            'id' => $categoryId
        ]));
    }
}
