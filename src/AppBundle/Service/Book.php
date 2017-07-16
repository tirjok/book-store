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
     * @param bool $addAuthor
     * @return array
     */
    public function bookSerializer($book, $addAuthor = true)
    {
        $authorService = $this->getContainer()->get('restapi.author');

        $data =  [
            'book_id' => $book->getId(),
            'name' => $book->getName(),
            'price' => (float) $book->getPrice(),
            'description' => $book->getDescription(),
            'isbn' => $book->getIsbn(),
        ];

        if ($addAuthor) {
            $data['author'] = $authorService->authorSerializer($book->getAuthor(), false);
        }

        return $data;
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
