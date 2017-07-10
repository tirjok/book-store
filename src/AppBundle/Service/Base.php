<?php

namespace AppBundle\Service;

use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

class Base
{
    protected $container;
    protected $serializer;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->serializer = null;
    }

    protected function getContainer()
    {
        return $this->container;
    }

    protected function getDoctrine()
    {
        return $this->container->get('doctrine');
    }

    protected function getSerializer()
    {
        if($this->serializer == null) {
            $this->serializer = \JMS\Serializer\SerializerBuilder::create()->build();
        }

        return $this->serializer;
    }

    public function serialize($object)
    {
        return $this->getSerializer()->serialize($object, 'json');
    }

}