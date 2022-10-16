<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;
use Faker\Factory;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\HttpFoundation\RequestStack;
use App\Entity\User;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\ORM\EntityManagerInterface;
use App\Factory\JsonResponseFactory;

class UserController extends AbstractController
{
    private RequestStack $requestStack;
    private EntityManagerInterface $entityManager;

    // public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager)
    // {
    //     $this->requestStack = $requestStack;
    //     $this->entityManager = $entityManager;
    // }

    private JsonResponseFactory $jsonResponseFactory;

    public function __construct(RequestStack $requestStack, EntityManagerInterface $entityManager, JsonResponseFactory $jsonResponseFactory)
    {
        $this->requestStack = $requestStack;
        $this->jsonResponseFactory = $jsonResponseFactory;
        $this->entityManager = $entityManager;
    }


    /**
     * @Route("/patients", name="patients")
     */
    public function patients(): Response
    {
        $session = $this->requestStack->getSession();

        if($session->get('logged_in') === true)
        {
            $user = $session->get('user');

            if($user->getRole() === 0)
            {
                $userRepo = $this->entityManager->getRepository(User::class)->findBy(['role' => 1]);

                for($i = 0; $i < count($userRepo); $i++)
                {
                    $userRepo[$i] = $this->jsonResponseFactory->create($userRepo[$i]);
                }

                return new JsonResponse($userRepo);
            }
        }

        return new JsonResponse(['status' => 'error', 'message' => 'You must be logged in before you can access this endpoint.']);
    }

    /**
     * @Route("/patient/{id}", name="patient")
     */
    public function patient(int $id): Response
    {
        $session = $this->requestStack->getSession();

        if($session->get('logged_in') === true)
        {
            $user = $session->get('user');

            if($user->getRole() === 0)
            {
                $userRepo = $this->entityManager->getRepository(User::class)->findOneBy(['id' => $id, 'role' => 1]);

                return new JsonResponse($this->jsonResponseFactory->create($userRepo));
            }
        }

        return new JsonResponse(['status' => 'error', 'message' => 'Patients does not have access to this endpoint.']);
    }
}
