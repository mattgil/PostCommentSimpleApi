<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 01.06.17
 * Time: 00:51
 */

namespace AppBundle\EventListener;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\ParameterBag;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;

class ContentTypeRequestListener
{
    public function onKernelRequest(GetResponseEvent $event)
    {
        $request = $event->getRequest();
        if ('json' ===  $request->getContentType()) {
            $data = json_decode($request->getContent(), true);
            if (!$data) {
                $event->setResponse(
                   new JsonResponse(['message' => 'non valid json format'], Response::HTTP_BAD_REQUEST)
               );
                return;
            }
            $request->request = new ParameterBag($data);
        }
    }
}
