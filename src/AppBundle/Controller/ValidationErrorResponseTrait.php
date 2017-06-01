<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 01.06.17
 * Time: 00:04
 */

namespace AppBundle\Controller;

use Symfony\Component\Form\FormErrorIterator;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;

trait ValidationErrorResponseTrait
{
    /**
     * @param FormInterface $form
     * @return JsonResponse
     */
    protected function prepareValidationErrorResponse(FormInterface $form):JsonResponse
    {
        $errorsArray = $this->getFormErrors($form);
        return new JsonResponse([
            'message' => 'validationErrors',
            'errors' => $errorsArray
        ], Response::HTTP_BAD_REQUEST);
    }

    /**
     * @param FormInterface $form
     * @return array
     */
    protected function getFormErrors(FormInterface $form): array
    {
        $errorsArray = [];
        $errors = $form->getErrors();
        foreach ($errors as $error) {
            $errorsArray[] = $error->getMessage();
        }

        foreach ($form->all() as $childForm) {
            if ($childForm instanceof FormInterface) {
                if ($childErrors = $this->getFormErrors($childForm)) {
                    $errorsArray[$childForm->getName()] = $childErrors;
                }
            }
        }
        return $errorsArray;
    }
}
