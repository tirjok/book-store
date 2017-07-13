<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class BaseController extends Controller
{
    public function createApiResponse($data, $status = 200)
    {
        return new JsonResponse([
            'success' => 'true',
            'data' => json_encode($data)
        ], $status);
    }
}