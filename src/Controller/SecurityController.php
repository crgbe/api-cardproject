<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\SessionInterface;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class SecurityController extends AbstractFOSRestController
{
    /**
     * @Rest\Route("/login", name="app_login", methods={"GET","POST"})
     */
    public function login(AuthenticationUtils $authenticationUtils, SerializerInterface $serializer, UrlGeneratorInterface $router)
    {
        $response = new Response();
        $data = ['message' => "Please, submit your tokenID via 'Authorization' header to ". $router->generate('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL)." or your user email to get a tokenID"];

        $lastUsername = $authenticationUtils->getLastUsername();
        $error = $authenticationUtils->getLastAuthenticationError();

//        if($lastUsername != null || $error->getMessageData()){
//            unset($data);
//            $data = [];
//            $data['lastUsername'] = $lastUsername;
//            $data['error'] = $serializer->serialize($error->getMessageData(), 'json');
//        }

        $data = $serializer->serialize($data, 'json');

        $response->setContent($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
