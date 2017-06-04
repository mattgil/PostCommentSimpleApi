<?php
/**
 * Created by PhpStorm.
 * User: mateusz
 * Date: 04.06.17
 * Time: 15:57
 */

namespace AppBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends Controller
{
    /**
     * @Route("/")
     */
    public function indexAction()
    {
        return new RedirectResponse("/doc");
    }
}
