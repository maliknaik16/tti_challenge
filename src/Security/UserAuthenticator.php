<?php

namespace App\Security;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\Exception\AuthenticationException;
use Symfony\Component\Security\Http\Authenticator\AbstractAuthenticator;
use Symfony\Component\Security\Http\Authenticator\Passport\Passport;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Security\Http\Authenticator\Passport\Badge\UserBadge;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\CustomCredentials;
use Symfony\Component\Security\Http\Authenticator\Passport\Credentials\PasswordCredentials;
use App\Entity\User;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\RequestStack;

class UserAuthenticator extends AbstractAuthenticator
{
    private UserRepository $userRepository;
    private RequestStack $requestStack;

    public function __construct(UserRepository $userRepository, RequestStack $requestStack)
    {
        $this->userRepository = $userRepository;
        $this->requestStack = $requestStack;
    }

    public function supports(Request $request): ?bool
    {
        $supported_on =
        [
            '/login',
        ];

        return in_array($request->getPathInfo(), $supported_on) ? true : false;
    }

    public function authenticate(Request $request): Passport
    {
        $requestStack = &$this->requestStack;
        // $session = $requestStack->getSession();

        // dd($session->get('logged_in'));

        // if($session->get('logged_in') === true)
        // {
        //     $user = $session->get('user');
        //     return new Passport(new UserBadge($user->getEmail()), new PasswordCredentials($user->getPassword()));
        // }

        $email = trim($request->request->get('email', ''));
        $password = trim($request->request->get('password', ''));
        return new Passport(
            new UserBadge($email, function(string $userIdentifier) {
                return $this->userRepository->findOneBy(['email' => $userIdentifier]);
            }),
            new CustomCredentials(function($credentials, User $user) use(&$requestStack) {
                if($user->getPassword() === $credentials)
                {
                    $session = $requestStack->getSession();
                    $session->set('logged_in', true);
                    $session->set('user', $user);

                    return true;
                }

                return false;
            }, $password),
        );
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, string $firewallName): ?Response
    {
        return null;
    }

    public function onAuthenticationFailure(Request $request, AuthenticationException $exception): ?Response
    {
        $data = [
            'status' => 'error',
            'message' => $request->getPathInfo() == '/login' ? 'Authentication Failed: Incorrect credentials' : 'You need permission to access this endpoint.',
        ];

        return new JsonResponse($data, Response::HTTP_UNAUTHORIZED);

    }

}
