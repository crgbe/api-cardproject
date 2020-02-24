<?php

namespace App\Controller;

use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class SecurityController extends AbstractFOSRestController
{
    /**
     * @Rest\Route("/login", name="app_login", methods={"GET","POST"})
     */
    public function login(UrlGeneratorInterface $router)
    {
        $response = new JsonResponse();
        $data = [
            'message' => "Please, submit your user email to ".$router->generate('app_login', [], UrlGeneratorInterface::ABSOLUTE_URL)." to get a tokenID",
        ];

        $response->setData($data);
        $response->headers->set('Content-Type', 'application/json');

        return $response;
    }
}
