<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 01.06.17
 * Time: 20:07
 */

namespace AppBundle\Controller;


use AppBundle\DTO\PostDTO;
use AppBundle\Entity\Post;
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
     * @Route("/posts", methods={"PUT", "POST"})
     */
    public function createPostsAction( Request $request, UserInterface $user )
    {
        $postDTO = new PostDTO();
        $postForm = $this->createForm(PostForm::class,$postDTO);
        $postForm->handleRequest($request);
        if($postForm->isValid()){
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
}