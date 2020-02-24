<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\UserProviderInterface;
use Symfony\Component\Security\Guard\Authenticator\AbstractFormLoginAuthenticator;

class LoginFormAuthenticator extends AbstractFormLoginAuthenticator
{
    private $userRepository;
    private $session;
    private $router;

    public function __construct(UserRepository $userRepository, SessionInterface $session, RouterInterface $router)
    {
        $this->userRepository = $userRepository;
        $this->session = $session;
        $this->router = $router;
    }

    protected function getLoginUrl()
    {
        return $this->router->generate('app_login');
    }

    public function supports(Request $request)
    {
        return 'app_login' === $request->attributes->get('_route') &&
            $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        return [
            'email' => $request->request->get('email'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        return $this->userRepository->findOneBy(['email' => $credentials['email']]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        if(!$this->session->get('tokens')){
            $sessionTokens = $this->session->set('tokens', []);
        }

        $sessionTokens = $this->session->get('tokens');

        /**@var User $user*/
        $user = $token->getUser();
        $response = new JsonResponse();
        $data = [
            'message' => "Vous êtes désormais authentifié. Utilisez ce token pour accéder à n'importe quelle ressources aux urls (/api)",
            'token' => bin2hex(random_bytes(60)),
        ];

        $sessionTokens[$user->getId()] = $data['token'];
        $this->session->set('tokens', $sessionTokens);


        $response->headers->set('Content-Type', 'application/json');
        $response->setData($data);

        return $response;
    }
}
