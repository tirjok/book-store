<?php

namespace AppBundle\Service;

use \InvalidArgumentException;
use \DateTime;
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
            return $this->getDoctrine()
                ->getRepository(BookEntity::class)
                ->fidAll();
        } catch (\Exception $e) {
            return [];
        }
    }
    public function getBookById($bookId)
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
    public function create($book)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($book);
        $em->flush();

        return $book;
    }

    /**
     * @param $book
     * @return array
     */
    public function bookSerializer($book)
    {
        return [
            'book_id' => $book->getId(),
            'name' => $book->getName(),
            'price' => (float) $book->getPrice(),
            'description' => $book->getDescription(),
        ];
    }

    public function setBook($bookData=null) {
        try {
            if(empty($bookData)) {
                throw new NotFoundHttpException (
                    'No book data found to save.'
                );                            
            }
            
            /** fill entity */
            $book = new BookEntity();
            $book->setTitle($bookData['title'])
                    ->setPublishDate(new DateTime($bookData['publish_date']))
                    ->setIsbn($bookData['isbn']);

            /** save entity */
            $em = $this->getDoctrine()->getManager();
            $em->persist($book);
            $em->flush();

            return $book;
        }
        catch (\Exception $ex) {
            throw $ex;
        }
    }
}