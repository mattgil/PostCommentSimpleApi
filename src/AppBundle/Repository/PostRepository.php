<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 01.06.17
 * Time: 21:18
 */

namespace AppBundle\Repository;

use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use Doctrine\ORM\EntityRepository;
use Doctrine\ORM\NoResultException;
use Doctrine\ORM\Query\Expr\Join;

class PostRepository extends EntityRepository
{
    public function findPostToListSortByDateDESC()
    {
        $qb = $this->createQueryBuilder('p');
        $qb->addSelect('u')
            ->join('p.user', 'u', Join::WITH)
            ->orderBy('p.date', "DESC");

        return array_map([$this, 'preparePostListView' ], $qb->getQuery()->getResult());
    }

    public function findPostDetails($post_id)
    {
        $qb = $this->createQueryBuilder('p');
        $qb->join('p.user', 'u', Join::WITH)
            ->leftJoin('p.comments', 'c', JOIN::WITH)
            ->leftJoin('c.user', 'cu', JOIN::WITH)
            ->where($qb->expr()->eq('p.id', ':post_id'));
        $qb->setParameter('post_id', $post_id);

        try {
            $post = $qb->getQuery()->getSingleResult();
            $postView = $this->preparePostListView($post);
            if (count($post->getComments())) {
                $commentsView = array_map([$this, 'prepareCommentListView'], $post->getComments()->toArray());
                $postView['comments'] = $commentsView;
            }
            return $postView;
        } catch (NoResultException $exception) {
            return null;
        }
    }

    private function preparePostListView(Post $post): array
    {
        return [
            'id' => $post->getId(),
            'title' => $post->getTitle(),
            'description' => $post->getDescription(),
            'date' => $post->getDate()->format('Y-m-d H:i:s'),
            'author' => [
                'name' => $post->getUser()->getName(),
                'surname' => $post->getUser()->getSurname()
            ]
        ];
    }


    private function prepareCommentListView(Comment $comment)
    {
        return [
                'comment'=> $comment->getComment(),
                'date' => $comment->getDate()->format('Y-m-d H:i:s'),
                'author' => [
                    'name' => $comment->getUser()->getName(),
                    'surname' => $comment->getUser()->getSurname()
                ]
            ];
    }
}
