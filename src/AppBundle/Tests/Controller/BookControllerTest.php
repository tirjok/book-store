<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public function testSinglebook()
    {
        $client = static::createClient();

        $crawler = $client->request('GET', '/book');
    }

}
