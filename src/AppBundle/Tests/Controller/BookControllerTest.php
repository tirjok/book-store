<?php

namespace AppBundle\Tests\Controller;

use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

class BookControllerTest extends WebTestCase
{
    public $client;

    protected function setUp()
    {
        $this->client = static::createClient();
    }

    public function testIndex()
    {
        $this->client->request('GET', '/api/v1/books');

        $this->assertEquals(401, $this->client->getResponse()->getStatusCode());
    }

    public function testShow()
    {
        $this->client->request('GET', '/api/v1/books/1');

        $this->assertEquals(200, $this->client->getResponse()->getStatusCode());
    }

    public function testStoreWhenUnauthorised()
    {
        $this->client->request('POST', '/api/v1/books');
        $expected = [
            'code' => 401,
            'message' => 'Invalid credentials'
        ];

        $this->assertEquals(json_encode($expected), $this->client->getResponse()->getContent());
    }
}
