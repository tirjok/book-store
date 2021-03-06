<?php

namespace AppBundle\Controller\V1;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Author;
use AppBundle\Form\AuthorType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class AuthorController extends BaseController
{
    /**
     * @Route("/authors",name="api_authors")
     * @Method("GET")
     */
    public function indexAction()
    {
        $authorService = $this->container->get('restapi.author');
        $authors = $authorService->all();

        return $this->createApiResponse(['authors' => $authors]);
    }

    /**
     * @Route("/authors",name="api_authors_create")
     * @Method("POST")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeAction(Request $request)
    {
        $authorService = $this->container->get('restapi.author');
        $author = new Author();
        $form = $this->createForm(new AuthorType(), $author);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $author = $authorService->persist($author);
        $authorUrl = $this->generateUrl(
            'api_authors_show',
            ['id' => $author->getId()]
        );

        $response = $this->createApiResponse($authorService->authorSerializer($author), 201);
        $response->headers->set('Location', $authorUrl);

        return $response;
    }

    /**
     * @Route("/authors/{id}",name="api_authors_update", requirements={"id": "\d+"})
     * @Method({"PUT", "PATCH"})
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction($id, Request $request)
    {
        $authorService = $this->container->get('restapi.author');

        try {
            $author = $authorService->find($id);
        } catch (NotFoundHttpException $e) {
            return $this->createNotFountErrorResponse('No author found with id ' . $id);
        }

        $form = $this->createForm(new AuthorType(), $author);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $author = $authorService->persist($author);

        return $this->createApiResponse($authorService->authorSerializer($author));
    }

    /**
     * @Route("/authors/{id}",name="api_authors_show", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param $id
     * @return JsonResponse
     */
    public function showAction($id)
    {
        $authorService = $this->container->get('restapi.author');

        try {
            $author = $authorService->find($id);
        } catch (NotFoundHttpException $e) {
            throw $this->createNotFoundException('No author found with id ' . $id);
        }

        return $this->createApiResponse($authorService->authorSerializer($author));
    }

    /**
     * @Route("/authors/{id}", name="api_authors_delete", requirements={"id": "\d+"})
     * @Method("DELETE")
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        $authorService = $this->container->get('restapi.author');

        try {
            $author = $authorService->find($id);
        } catch (NotFoundHttpException $e) {
            throw $this->createNotFoundException('No author found with id ' . $id);
        }

        $authorService->remove($author);

        return $this->createApiResponse([], 204);
    }
}
