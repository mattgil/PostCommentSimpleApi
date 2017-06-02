<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 01.06.17
 * Time: 20:07
 */

namespace AppBundle\Controller;

use AppBundle\DTO\CommentDTO;
use AppBundle\DTO\PostDTO;
use AppBundle\Entity\Comment;
use AppBundle\Entity\Post;
use AppBundle\Form\CommentForm;
use AppBundle\Form\PostForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\User\UserInterface;

class PostController extends Controller
{
    use ValidationErrorResponseTrait;

    /**
     * @Route("/posts", methods={"GET"})
     */
    public function getPostsAction()
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->findPostToListSortByDateDESC();

            return new JsonResponse(['posts' => $posts]);

    }

    /**
     * @Route("/posts/{post_id}", requirements={"post_id"="\d+"}, methods={"GET"})
     */
    public function getPostDetailAction($post_id)
    {
        $post = $this->getDoctrine()->getRepository("AppBundle:Post")->findPostDetails($post_id);
        if( !$post ){
            return new JsonResponse(['message'=> 'post does not exists'], Response::HTTP_NOT_FOUND);
        } else {
            return new JsonResponse(['post' => $post]);
        }
    }

    /**
     * @Route("/posts", methods={"PUT", "POST"})
     */
    public function createPostsAction(Request $request, UserInterface $user)
    {
        $postDTO = new PostDTO();
        $postForm = $this->createForm(PostForm::class, $postDTO);
        $postForm->handleRequest($request);
        if ($postForm->isValid()) {
            $post = new Post(
                $user,
                $postDTO->title,
                $postDTO->description
            );
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($post);
            $em->flush();
            return new JsonResponse(['message' => 'Post created'], Response::HTTP_CREATED);
        } else {
            return $this->prepareValidationErrorResponse($postForm);
        }
    }

    /**
     * @Route("/posts/{post_id}", requirements={"post_id"="\d+"}, methods={"DELETE"})
     */
    public function deletePost($post_id, UserInterface $user)
    {
        $post = $this->getDoctrine()->getRepository("AppBundle:Post")->find($post_id);
        if(!$post){
            return new JsonResponse(['message'=> 'post does not exists'], Response::HTTP_NOT_FOUND);
        }
        if( $post->getUser() !== $user ){
            return new JsonResponse(['message'=> 'can not delete post'], Response::HTTP_UNAUTHORIZED);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $em->remove($post);
        $em->flush();
        return new JsonResponse(['message'=> 'post was deleted']);
    }

    /**
     * @Route("/posts/{post_id}/comment", requirements={"post_id"="\d+"}, methods={"POST", "PUT"})
     */
    public function createCommentAction(Request $request, $post_id, UserInterface $user)
    {
        $post = $this->getDoctrine()->getRepository('AppBundle:Post')->find($post_id);
        if (!$post) {
            return new JsonResponse(['message' => 'Post does not exists'], Response::HTTP_NOT_FOUND);
        }
        $commentDTO = new CommentDTO();
        $commentFrom = $this->createForm(CommentForm::class, $commentDTO);
        $commentFrom->handleRequest($request);
        if ($commentFrom->isValid()) {
            $comment = new Comment($post, $user, $commentDTO->comment);
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($comment);
            $em->flush();
            return new JsonResponse(['message'=> 'Comment created']);
        } else {
            return $this->prepareValidationErrorResponse($commentFrom);
        }
    }
}
