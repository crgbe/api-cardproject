<?php

namespace App\Controller;

use App\Repository\CardRepository;
use FOS\RestBundle\Controller\Annotations as Rest;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @Rest\Route("/api", name="api_")
 */
class CardController extends AbstractController
{
    /**
     * @Rest\Get(
     *     path="/cards",
     *     name="cards"
     * )
     * @Rest\View()
     */
    public function index(CardRepository $cardRepository)
    {
        return $cardRepository->findAll();
    }
}
