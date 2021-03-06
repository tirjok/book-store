<?php

namespace AppBundle\Controller\V1;

use AppBundle\Controller\BaseController;
use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class BookController extends BaseController
{
    /**
     * @Route("/books",name="api_books")
     * @Method("GET")
     */
    public function indexAction()
    {
        $bookService = $this->container->get('restapi.book');
        $books = $bookService->all();

        return $this->createApiResponse(['books' => $books]);
    }

    /**
     * @Route("/books",name="api_books_create")
     * @Method("POST")
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function storeAction(Request $request)
    {
        $bookService = $this->container->get('restapi.book');
        $book = new Book();
        $form = $this->createForm(new BookType(), $book);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $book = $bookService->persist($book);
        $bookUrl = $this->generateUrl(
            'api_books_show',
            ['id' => $book->getId()]
        );

        $response = $this->createApiResponse($bookService->bookSerializer($book), 201);
        $response->headers->set('Location', $bookUrl);

        return $response;
    }

    /**
     * @Route("/books/{id}", name="api_books_update", requirements={"id": "\d+"})
     * @Method({"PUT", "PATCH"})
     *
     * @param $id
     * @param Request $request
     * @return JsonResponse
     */
    public function updateAction($id, Request $request)
    {
        $bookService = $this->container->get('restapi.book');

        try {
            $book = $bookService->find($id);
        } catch (NotFoundHttpException $e) {
            return $this->createNotFountErrorResponse('No book found with id ' . $id);
        }

        $form = $this->createForm(new BookType(), $book);
        $this->processForm($request, $form);

        if (!$form->isValid()) {
            $this->throwApiProblemValidationException($form);
        }

        $book = $bookService->persist($book);

        return $this->createApiResponse($bookService->bookSerializer($book));
    }

    /**
     * @Route("/books/{id}", name="api_books_show", requirements={"id": "\d+"})
     * @Method("GET")
     *
     * @param $id
     * @return JsonResponse
     */
    public function showAction($id)
    {
        $bookService = $this->container->get('restapi.book');

        try {
            $book = $bookService->find($id);
        } catch (NotFoundHttpException $e) {
            throw $this->createNotFoundException('No book found with id ' . $id);
        }

        return $this->createApiResponse($bookService->bookSerializer($book));
    }

    /**
     * @Route("/books/{id}", name="api_books_delete", requirements={"id": "\d+"})
     * @Method("DELETE")
     *
     * @param $id
     * @return JsonResponse
     */
    public function deleteAction($id)
    {
        $bookService = $this->container->get('restapi.book');

        try {
            $book = $bookService->find($id);
        } catch (NotFoundHttpException $e) {
            throw $this->createNotFoundException('No book found with id ' . $id);
        }

        $bookService->remove($book);

        return $this->createApiResponse([], 204);
    }
}
