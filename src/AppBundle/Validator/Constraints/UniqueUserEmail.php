<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 02.06.17
 * Time: 08:51
 */

namespace AppBundle\Validator\Constraints;

use Symfony\Component\Validator\Constraint;

/**
 * @Annotation
 */
class UniqueUserEmail extends Constraint
{
    public $message = "email: {{ email }} already exists";

    public function validatedBy()
    {
        return UniqueUserEmailValidator::class;
    }
}
