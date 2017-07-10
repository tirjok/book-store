<?php

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Symfony\Component\HttpFoundation\JsonResponse;

class BookController extends Controller
{
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

}
