<?php

namespace App\Security;

use App\Entity\User;
use App\Repository\UserRepository;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
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
        if(!$this->session->get('tokens')){
            $this->session->set('tokens', []);
        }

        return 'app_login' === $request->attributes->get('_route')
            && $request->isMethod('POST');
    }

    public function getCredentials(Request $request)
    {
        if($request->headers->has('Authorization') && 0 === strpos($request->headers->get('Authorization'), 'Bearer ')){
            return [
                'token' => substr($request->headers->get('Authorization'), 7),
            ];
        }

        return [
            'email' => $request->request->get('email'),
        ];
    }

    public function getUser($credentials, UserProviderInterface $userProvider)
    {
        $sessionTokens = $this->session->get('tokens');

        if(isset($credentials['token'])){
            if(in_array($credentials['token'], $sessionTokens)){
                return $this->userRepository->find(array_search($credentials['token'], $sessionTokens));
            }
        }

        return $this->userRepository->findOneBy(['email' => $credentials['email']??null]);
    }

    public function checkCredentials($credentials, UserInterface $user)
    {
//        return isset($credentials['token']);
        return true;
    }

    public function onAuthenticationSuccess(Request $request, TokenInterface $token, $providerKey)
    {
        $response = new JsonResponse();

        /**@var User $user*/
        $user = $token->getUser();
        $sessionTokens = $this->session->get('tokens');

        $data = [
            'message' => "Vous êtes désormais authentifié, vous pouvez accéder à n'importe quelle ressources aux urls (/api)"
        ];

        if(!array_key_exists($user->getId(), $sessionTokens)){
            unset($data);
            $sessionTokens[$user->getId()] = bin2hex(random_bytes(60));
            $this->session->set('tokens', $sessionTokens);

            $data = [
                'message' => "S'il vous plaît, utilisez le token ci-dessous pour vous connecter",
                'token' => $sessionTokens[$user->getId()],
            ];

            return $response->setData($data);
        }

        return $response->setData($data);
    }
}
