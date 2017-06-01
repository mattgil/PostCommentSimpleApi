<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 01.06.17
 * Time: 21:18
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\Query\Expr\Join;

class PostRepository extends EntityRepository
{
    public function findPostToListSortByDateDESC()
    {
        $qb = $this->createQueryBuilder('p');
        $qb->addSelect('u')
            ->join('p.user', 'u', Join::WITH)
            ->orderBy('p.date', "DESC");

        return array_map(function (Post $post) {
            return [
                                'title' => $post->getTitle(),
                                'description' => $post->getDescription(),
                                'date' => $post->getDate()->format('Y-m-d H:i:s'),
                                'author' => [
                                    'name' => $post->getUser()->getName(),
                                    'surname' => $post->getUser()->getSurname()
                                ]
                            ];
        }, $qb->getQuery()->getResult());
    }
}
