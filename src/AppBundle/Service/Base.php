<?php

namespace AppBundle\Service;

use InvalidArgumentException;
use Symfony\Component\DependencyInjection\ContainerInterface as Container;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Serializer\Encoder\XmlEncoder;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Serializer\Encoder\JsonEncoder;
use Symfony\Component\Serializer\Normalizer\ObjectNormalizer;

abstract class Base
{
    protected $container;
    protected $serializer;

    public function __construct(Container $container) {
        $this->container = $container;
        $this->serializer = null;
    }

    abstract public function getEntity();

    protected function getContainer()
    {
        return $this->container;
    }

    protected function getDoctrine()
    {
        return $this->container->get('doctrine');
    }

    /**
     * @return null|Serializer
     */
    protected function getSerializer()
    {
        if ($this->serializer == null) {
            $encoders = array(new XmlEncoder(), new JsonEncoder());
            $normalizers = array(new ObjectNormalizer());
            $this->serializer = new Serializer($normalizers, $encoders);
        }

        return $this->serializer;
    }

    public function serialize($object)
    {
        return $this->getSerializer()->serialize($object, 'json');
    }

    /**
     * Remove an item from storage
     *
     * @param $object
     * @return mixed
     */
    public function remove($object)
    {
        $em = $this->getDoctrine()->getManager();
        $em->remove($object);
        return $em->flush();
    }


    /**
     * Persist an item
     *
     * @param $object
     * @return array
     */
    public function persist($object)
    {
        $em = $this->getDoctrine()->getManager();
        $em->persist($object);
        $em->flush();

        return $object;
    }

    /**
     * @param $id
     * @return mixed
     * @throws \Exception
     */
    public function find($id)
    {
        try {
            if(empty($id)) {
                throw new InvalidArgumentException ("Book id can not be empty.");
            }

            $book = $this->getDoctrine()
                ->getRepository($this->getEntity())
                ->find($id);

            if (!$book) {
                throw new NotFoundHttpException (
                    'No book found for id '.$id
                );
            }

            return $book;
        }
        catch (\Exception $ex) {
            throw $ex;
        }
    }

    /**
     * @return mixed
     */
    public function findAll()
    {
        return  $this->getDoctrine()
            ->getRepository($this->getEntity())
            ->findAll();
    }
}