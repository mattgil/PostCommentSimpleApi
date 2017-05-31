<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 31.05.17
 * Time: 19:50
 */

namespace AppBundle\Entity;


class Comment
{
    private $id;

    private $post;

    private $user;

    private $comment;

    private $date;

    /**
     * Comment constructor.
     * @param Post $post
     * @param User $user
     * @param string $comment
     */
    public function __construct(Post $post, User $user, string $comment)
    {
        $this->post = $post;
        $this->user = $user;
        $this->comment = $comment;
        $this->date = new \DateTime;
    }

    /**
     * @return Post
     */
    public function getPost(): Post
    {
        return $this->post;
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
    public function getComment(): string
    {
        return $this->comment;
    }

    /**
     * @return \DateTime
     */
    public function getDate(): \DateTime
    {
        return $this->date;
    }


}