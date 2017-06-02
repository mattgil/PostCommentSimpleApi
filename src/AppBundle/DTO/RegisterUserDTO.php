<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 31.05.17
 * Time: 23:01
 */

namespace AppBundle\DTO;

use Symfony\Component\Validator\Constraints as Assert;
use AppBundle\Validator\Constraints as AppAssert;

class RegisterUserDTO
{
    /**
     * @Assert\NotBlank(message="name can not be blank")
     */
    public $name;

    /**
     * @Assert\NotBlank(message="surname can not be blank")
     */
    public $surname;

    /**
     * @Assert\NotBlank(message="password can not be blank")
     * @Assert\Length(min="8", minMessage="password is too short, minimum is 8 characters")
     */
    public $password;

    /**
     * @Assert\Email(message="email is not valid")
     * @Assert\NotBlank(message="email can not be blank")
     * @AppAssert\UniqueUserEmail
     */
    public $email;
}
