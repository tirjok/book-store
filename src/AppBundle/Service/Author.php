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
            $items =  $this->getDoctrine()
                ->getRepository(AuthorEntity::class)
                ->findAll();

            $data = [];

            foreach ($items as $item) {
                $data[] = $this->authorSerializer($item);
            }

            return $data;
        } catch (\Exception $e) {
            return [];
        }
    }

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
            'birthday' => $author->getBirthday()
        ];
    }
}