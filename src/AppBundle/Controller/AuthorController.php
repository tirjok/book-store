<?php

namespace AppBundle\Controller;

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
     * @Route("/api/authors",name="api_authors")
     * @Method("GET")
     */
    public function indexAction()
    {
        $authorService = $this->container->get('restapi.author');
        $authors = $authorService->all();

        return $this->createApiResponse(['authors' => $authors]);
    }

    /**
     * @Route("/api/authors",name="api_authors_create")
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
            return $this->createValidationErrorResponse($form);
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
     * @Route("/api/authors/{id}",name="api_authors_update")
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
            return $this->createValidationErrorResponse($form);
        }

        $author = $authorService->persist($author);

        return $this->createApiResponse($authorService->authorSerializer($author));
    }

    /**
     * @Route("/api/authors/{id}",name="api_authors_show")
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
            return $this->createNotFountErrorResponse('No author found with id ' . $id);
        }

        return $this->createApiResponse($authorService->authorSerializer($author));
    }

    /**
     * @Route("/api/authors/{id}", name="api_authors_delete")
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
            return $this->createNotFountErrorResponse('No author found with id ' . $id);
        }

        $authorService->remove($author);

        return $this->createApiResponse([], 204);
    }
}
