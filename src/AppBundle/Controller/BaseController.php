<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

abstract class BaseController extends Controller
{
    /**
     * @param $data
     * @param int $status
     * @return JsonResponse
     */
    public function createApiResponse($data, $status = 200)
    {
        return new JsonResponse($data, $status);
    }
}