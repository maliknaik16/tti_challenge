<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\HttpFoundation\JsonResponse;

class SecurityController extends AbstractController
{
    public function __construct(RequestStack $requestStack)
    {
        $this->requestStack = $requestStack;
    }

    /**
     * @Route("/login", name="login_endpoint")
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {

        return new JsonResponse(['logged_in' => true]);
    }

    /**
     * @Route("/logout", name="logout_endpoint")
     */

    public function logout(AuthenticationUtils $authenticationUtils): Response
    {
        if($this->requestStack->getSession())
        {
            $this->requestStack->getSession()->clear();
        }

        return new JsonResponse(['logged_in' => false]);
    }
}
