<?php

namespace AppBundle\Service;

use AppBundle\Entity\Author as AuthorEntity;

class Author extends Base
{
    /**
     * @return array
     */
    public function all()
    {
        try {
            $items =  $this->findAll();

            $data = [];

            foreach ($items as $item) {
                $data[] = $this->authorSerializer($item);
            }

            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

    protected function getAuthorBooks($author)
    {
        $bookService = $this->getContainer()->get('restapi.book');
        $lists = [];

        foreach ($author->getBooks() as $item) {
            $lists[] = $bookService->bookSerializer($item, false);
        }

        return $lists;
    }

    /**
     * @param $author
     * @param bool $addBooks
     * @return array
     */
    public function authorSerializer($author, $addBooks = true)
    {
        $data = [
            'author_id' => $author->getId(),
            'name' => $author->getName(),
            'email' => $author->getEmail(),
            'birthday' => $author->getBirthday(),
        ];

        if ($addBooks) {
            $data['books'] = $this->getAuthorBooks($author);
        }

        return $data;
    }

    /**
     * @return string
     */
    public function getEntity()
    {
        return AuthorEntity::class;
    }
}