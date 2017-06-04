<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 01.06.17
 * Time: 19:36
 */

namespace AppBundle\Security;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\AbstractGuardAuthenticator;

class Authenticator extends AbstractGuardAuthenticator
{
    public function start(Request $request, AuthenticationException $authException = null)
    {
        $data = [
            'message' => 'Authentication Required'
        ];
        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);
    }

    public function getCredentials(Request $request)
    {
        if (!$credentials = $request->headers->get('X-AUTH')) {
            return null;
        }
        $credentials_array = explode(":", $credentials);

        if (count($credentials_array) !== 2) {
            return null;
        }

        list($email, $password) = $credentials_array;
        return [
            'email' => $email,
            'password' => $password
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        if (!$credentials || !$credentials['email'] || !$credentials['password']) {
            return null;
        }

        return $userProvider->loadUserByUsername($credentials['email']);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return password_verify($credentials['password'], $user->getPassword());
    }


    public function onAuthenticationFailure(Request $request, AuthenticationException $exception)
    {
        $data =[
            'message' => 'authentication failed'
        ];
        return new JsonResponse($data, Response::HTTP_FORBIDDEN);
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        return null;
    }

    public function supportsRememberMe()
    {
        return false;
    }
}
