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
use Nelmio\ApiDocBundle\Annotation\ApiDoc;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * @Route("/user")
 */
class UserController extends Controller
{
    use ValidationErrorResponseTrait;

    /**
     * @ApiDoc(
     *     section="user",
     *     description="User registration method",
     *     input="AppBundle\DTO\RegisterUserDTO",
     *     statusCodes={
     *          201="When user created",
     *          400="when not valid json were passed or validation errors occur "
     *     }
     *)
     * @Route("/register", methods={"POST"})
     */
    public function registerUserAction(Request $request)
    {
        $registerUserDTO = new RegisterUserDTO();
        $registerUserFrom = $this->createForm(RegisterUserForm::class, $registerUserDTO);
        $registerUserFrom->handleRequest($request);

        if ($registerUserFrom->isValid()) {
            $em = $this->get('doctrine.orm.entity_manager');
            $user = new User(
                $registerUserDTO->email,
                $registerUserDTO->name,
                $registerUserDTO->surname,
                $registerUserDTO->password
            );

            $em->persist($user);
            $em->flush();
            return new JsonResponse(['message' => 'user successfully created'], Response::HTTP_CREATED);
        } else {
            return $this->prepareValidationErrorResponse($registerUserFrom);
        }
    }
}
