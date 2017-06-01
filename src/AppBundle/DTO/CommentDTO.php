<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 01.06.17
 * Time: 22:05
 */

namespace AppBundle\DTO;

use Symfony\Component\Validator\Constraints as Assert;

class CommentDTO
{
    /**
     * @Assert\NotBlank(message="comment can not be blank")
     */
    public $comment;
}