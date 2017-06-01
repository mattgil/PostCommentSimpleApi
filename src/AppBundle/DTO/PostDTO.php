<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 01.06.17
 * Time: 20:35
 */

namespace AppBundle\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class PostDTO
{
    /**
     * @Assert\NotBlank(message="title can not be blank")
     * @Assert\Length(max=32, maxMessage="title can be max 32 characters")
     */
    public $title;

    /**
     * @Assert\NotBlank(message="description can not be blank")
     */
    public $description;
}