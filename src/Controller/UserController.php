<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Faker\Factory;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class UserController extends AbstractController
{
    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    /**
     * @Route("/patients", name="patients")
     */
    public function patients(AuthenticationUtils $authenticationUtils): Response
    {
        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        // dd($error);
        dd($authenticationUtils->getRequest()->getSession());

        dd($this->security->getUser());
        $faker = Factory::create();

        $output = '';

        $output .= $faker->firstName(). ' ';
        $output .= $faker->email(). ' ';
        $output .= $faker->address(). ' ';
        $output .= $faker->phoneNumber(). ' ';

        return new Response($output);
    }
}
