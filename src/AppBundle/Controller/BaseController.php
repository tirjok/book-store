<?php

namespace AppBundle\Controller;


use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\HttpException;

abstract class BaseController extends Controller
{
    /**
     * @param $data
     * @param int $status
     * @return JsonResponse
     */
    public function createApiResponse($data, $status = 200)
    {
        $response = new JsonResponse($data, $status);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }

    /**
     * @param FormInterface $form
     * @return JsonResponse
     */
    public function createValidationErrorResponse(FormInterface $form)
    {
        $errors = $this->getErrorsFromForm($form);

        $data = [
            'type' => 'validation_error',
            'title' => 'There was a validation error',
            'errors' => $errors
        ];

        return $this->createApiResponse($data, 400);
    }

    /**
     * @param $message
     * @return JsonResponse
     */
    public function createNotFountErrorResponse($message)
    {
        $data = [
            'type' => 'not_found_error',
            'title' => 'Not Found',
            'message' => $message
        ];

        return $this->createApiResponse($data, 404);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    public function getErrorsFromForm(FormInterface $form)
    {
        $errors = array();
        foreach ($form->getErrors() as $error) {
            $errors[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getErrorsFromForm($childForm)) {
                    $errors[$childForm->getName()] = $childErrors;
                }
            }
        }

        return $errors;
    }

    /**
     * @param Request $request
     * @param FormInterface $form
     */
    public function processForm(Request $request, FormInterface $form)
    {
        $data = $this->getRequestArray($request);

        $clearMissing = $request->getMethod() != 'PATCH';

        $form->submit($data, $clearMissing);
    }

    /**
     * @param Request $request
     * @return mixed
     */
    public function getRequestArray(Request $request)
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            throw new HttpException(400, 'Invalid JSON body!');
        }

        return $data;
    }
}
