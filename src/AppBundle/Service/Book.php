<?php

namespace AppBundle\Service;

use AppBundle\Entity\Book as BookEntity;

class Book extends Base
{
    /**
     * @return array
     */
    public function all()
    {
        try {
            $books =  $this->findAll();

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

    /**
     * @param $book
     * @return array
     */
    public function persist($book)
    {
        $book->setAuthor($book->getAuthorId());

        return parent::persist($book);
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return BookEntity::class;
    }
}
