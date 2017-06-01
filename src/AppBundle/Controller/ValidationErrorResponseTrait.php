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
        ], 400);
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