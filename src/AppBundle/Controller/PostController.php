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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
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
     * @ApiDoc(
     *     section="post",
     *     description="returns list of post sorted by date",
     *     headers={
     *          {
     *              "name"="X-AUTH",
     *              "description"="authorisation data in email:password format"
     *          }
     *     },
     *     statusCodes={
                200="return list of posts",
     *          403="credentials not valid",
     *          401={"authorization header X-AUTH is missing "," value is not in email:password format"}
     *     }
     *
     * )
     */
    public function getPostsAction()
    {
        $posts = $this->getDoctrine()->getRepository('AppBundle:Post')->findPostToListSortByDateDESC();

        return new JsonResponse(['posts' => $posts]);
    }

    /**
     * @Route("/posts/{post_id}", requirements={"post_id"="\d+"}, methods={"GET"})
     * @ApiDoc(
     *     section="post",
     *     description="returns post detail with comments",
     *     headers={
     *          {
     *              "name"="X-AUTH",
     *              "description"="authorisation data in email:password format",
     *
     *          }
     *     },
     *     statusCodes={
     *          200="return post details",
     *          403="credentials not valid",
     *          401={"authorization header X-AUTH is missing "," value is not in email:password format"},
     *          404="post of given id was not found"
     *     }
     *
     * )
     */
    public function getPostDetailAction($post_id)
    {
        $post = $this->getDoctrine()->getRepository("AppBundle:Post")->findPostDetails($post_id);
        if (!$post) {
            return new JsonResponse(['message'=> 'post does not exists'], Response::HTTP_NOT_FOUND);
        } else {
            return new JsonResponse(['post' => $post]);
        }
    }

    /**
     * @Route("/posts", methods={"POST"})
     * @ApiDoc(
     *     section="post",
     *     description="post creation method",
     *     headers={
     *          {
     *              "name"="X-AUTH",
     *              "description"="authorisation data in email:password format",
     *
     *          }
     *     },
     *     input="AppBundle\DTO\PostDTO",
     *     statusCodes={
     *          201="When post created",
     *          403="credentials not valid",
     *          401={"authorization header X-AUTH is missing "," value is not in email:password format"},
     *          400={"when not valid json were passed "," validation errors occur "}
     *     }
     *)
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
     * @ApiDoc(
     *     section="post",
     *     description="deletes post",
     *     headers={
     *          {
     *              "name"="X-AUTH",
     *              "description"="authorisation data in email:password format",
     *
     *          }
     *     },
     *     statusCodes={
     *          200="post deleted",
     *          403={"credentials not valid "," user try delete post which not owns"},
     *          401={"authorization header X-AUTH is missing "," value is not in email:password format"},
     *          404="post of given id was not found"
     *     }
     *
     * )
     */
    public function deletePost($post_id, UserInterface $user)
    {
        $post = $this->getDoctrine()->getRepository("AppBundle:Post")->find($post_id);
        if (!$post) {
            return new JsonResponse(['message'=> 'post does not exists'], Response::HTTP_NOT_FOUND);
        }
        if ($post->getUser() !== $user) {
            return new JsonResponse(['message'=> 'can not delete post'], Response::HTTP_UNAUTHORIZED);
        }
        $em = $this->get('doctrine.orm.entity_manager');
        $em->remove($post);
        $em->flush();
        return new JsonResponse(['message'=> 'post was deleted']);
    }

    /**
     * @Route("/posts/{post_id}/comment", requirements={"post_id"="\d+"}, methods={"POST"})
     * @ApiDoc(
     *     section="post",
     *     description="comment creation method",
     *     headers={
     *          {
     *              "name"="X-AUTH",
     *              "description"="authorisation data in email:password format",
     *          }
     *     },
     *     input="AppBundle\DTO\CommentDTO",
     *     statusCodes={
     *          201="When comment created",
     *          403="credentials not valid",
     *          401={"authorization header X-AUTH is missing "," value is not in email:password format"},
     *          400={"when not valid json were passed "," validation errors occur "}
     *     }
     *)
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
            return new JsonResponse(['message'=> 'Comment created'], Response::HTTP_CREATED);
        } else {
            return $this->prepareValidationErrorResponse($commentFrom);
        }
    }
}
