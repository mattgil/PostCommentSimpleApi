<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 31.05.17
 * Time: 23:29
 */

namespace AppBundle\Controller;

use AppBundle\DTO\RegisterUserDTO;
use AppBundle\Entity\User;
use AppBundle\Form\RegisterUserForm;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    use ValidationErrorResponseTrait;

    /**
     * @Route("/register", methods={"PUT","POST"})
     */
    public function registerUserAction(Request $request)
    {
        $registerUserDTO = new RegisterUserDTO();
        $registerUserFrom = $this->createForm(RegisterUserForm::class, $registerUserDTO);
        $registerUserFrom->handleRequest($request);

        if ($registerUserFrom->isValid()) {
            $user = new User(
                $registerUserDTO->email,
                $registerUserDTO->name,
                $registerUserDTO->surname,
                $registerUserDTO->password
            );
            $em = $this->get('doctrine.orm.entity_manager');
            $em->persist($user);
            $em->flush();
            return new JsonResponse(['message' => 'user successfully created']);
        } else {
            return $this->prepareValidationErrorResponse($registerUserFrom);
        }
    }
}