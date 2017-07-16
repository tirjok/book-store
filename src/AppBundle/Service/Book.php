<?php

namespace AppBundle\Service;

use \InvalidArgumentException;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use AppBundle\Entity\Book as BookEntity;

class Book extends Base
{
    /**
     * @return array
     */
    public function all()
    {
        try {
            $books =  $this->getDoctrine()
                ->getRepository(BookEntity::class)
                ->findAll();

            $data = [];

            foreach ($books as $book) {
                $data[] = $this->bookSerializer($book);
            }

            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    /**
     * @param $bookId
     * @return mixed
     * @throws \Exception
     */
    public function find($bookId)
    {
        try {
            if(empty($bookId)) {
                throw new InvalidArgumentException ("Book id can not be empty.");
            }

            $book = $this->getDoctrine()
                        ->getRepository(BookEntity::class)
                        ->find($bookId);

            if (!$book) {
                throw new NotFoundHttpException (
                    'No book found for id '.$bookId
                );
            }

            return $book;
        }
        catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @param $book
     * @return array
     */
    public function bookSerializer($book)
    {
        $authorService = $this->getContainer()->get('restapi.author');

        return [
            'book_id' => $book->getId(),
            'name' => $book->getName(),
            'price' => (float) $book->getPrice(),
            'description' => $book->getDescription(),
            'isbn' => $book->getIsbn(),
            'author' => $authorService->authorSerializer($book->getAuthor())
        ];
    }

    public function persist($book)
    {
        $book->setAuthor($book->getAuthorId());

        return parent::persist($book);
    }
}
