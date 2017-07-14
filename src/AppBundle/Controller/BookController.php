<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Book;
use AppBundle\Form\BookType;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

class BookController extends BaseController
{
    /**
     * @Route("/books",name="books")
     * @Method("GET")
     */
    public function indexAction()
    {
        $serializer = $this->get('serializer');
        $bookService = $this->container->get('restapi.book');
        $books = $bookService->all();

        return new JsonResponse ([
            'success' => true,
            'data' => $serializer->serialize($books, 'json')
        ]);
    }

    /**
     * @Route("/api/books",name="api_books_create")
     * @Method("POST")
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
            $errors = $this->getErrorsFromForm($form);

            $data = [
                'type' => 'validation_error',
                'title' => 'There was a validation error',
                'errors' => $errors
            ];

            return $this->createApiResponse($data, 400);
        }

        $book = $bookService->create($book);
        $bookUrl = $this->generateUrl(
            'api_books_show',
            ['id' => $book->getId()]
        );

        $response = $this->createApiResponse($bookService->bookSerializer($book), 201);
        $response->headers->set('Location', $bookUrl);

        return $response;
    }

    /**
     * @Route("/api/books/{id}",name="api_books_show")
     * @Method("GET")
     */
    public function showAction($id)
    {

    }

    /**
     * @Route("/book/{id}",
     *          name="book_single",
     *          defaults={"id" = 0})
     * @Method({"GET", "PUT"})
     */
    public function singleBookAction($id)
    {
        try {
            if($this->getRequest()->isMethod("GET") ) {
                $bookService = $this->container->get('restapi.book');
                $book = $bookService->getBookById($id);
                return new JsonResponse ([
                    'success' => 'true',
                    'data' => $bookService->serialize($book)
                ]);
            }
            else if ($this->getRequest()->isMethod("PUT")) {
                $content = $this->getRequest()->getContent();

                $bookService = $this->container->get('restapi.book');
                $book = $bookService->setBook(json_decode($content, true));

                return new JsonResponse([
                    'success' => 'true',
                    'data' => $bookService->serialize($book)
                ]);
            }
        } catch (\Exception $ex) {
            return new JsonResponse ([
                'success' => 'false',
                'message' => $ex->getMessage()
            ]);
        }
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     */
    private function processForm(Request $request, FormInterface $form)
    {
        $data = json_decode($request->getContent(), true);

        $clearMissing = $request->getMethod() != 'PATCH';

        $form->submit($data, $clearMissing);
    }
}
