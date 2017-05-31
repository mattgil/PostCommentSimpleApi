<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 31.05.17
 * Time: 19:50
 */

namespace AppBundle\Entity;

use Doctrine\Common\Collections\ArrayCollection;

class Post
{
    private $id;

    private $user;

    private $title;

    private $description;

    private $date;

    private $comments;

    /**
     * Post constructor.
     * @param User $user
     * @param string $title
     * @param string $description
     */
    public function __construct(User $user, string $title, string $description)
    {
        $this->user = $user;
        $this->title = $title;
        $this->description = $description;
        $this->date = new \DateTime;
        $this->comments = new ArrayCollection();
    }

    /**
     * @return User
     */
    public function getUser(): User
    {
        return $this->user;
    }

    /**
     * @return string
     */
    public function getTitle(): string
    {
        return $this->title;
    }

    /**
     * @return string
     */
    public function getDescription(): string
    {
        return $this->description;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }

    /**
     * @return Comment[]
     */
    public function getComments(): ArrayCollection
    {
        return $this->comments;
    }
}
