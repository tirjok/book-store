<?php

namespace AppBundle\Service;


class Author extends Base
{
    /**
     * @param $author
     * @return array
     */
    public function authorSerializer($author)
    {
        return [
            'author_id' => $author->getId(),
            'name' => $author->getName(),
            'email' => $author->getEmail(),
            'date_of_birth' => $author->getDob()
        ];
    }
}