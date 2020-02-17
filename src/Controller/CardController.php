<?php

namespace App\Controller;

use App\Entity\Card;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\Controller\FOSRestController;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Rest\Route("/api", name="api_")
 */
class CardController extends FOSRestController
{
    private $em;

    public function __construct(EntityManagerInterface $em)
    {
        $this->em = $em;
    }

    /**
     * @Rest\Get(
     *     path="/cards",
     *     name="cards_index"
     * )
     * @Rest\View()
     */
    public function index(CardRepository $cardRepository)
    {
        return $cardRepository->findAll();
    }

    /**
     * @Rest\Get(
     *     path="/cards/{id}",
     *     name="cards_show",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View()
     */
    public function show(Card $card){
        return $card;
    }

    /**
     * @Rest\Post(
     *     path="/cards",
     *     name="cards_add"
     * )
     * @ParamConverter("card", converter="fos_rest.request_body")
     */
    public function add(Card $card){
        $this->em->persist($card);
        $this->em->flush();

        return $this->view($card, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('api_cards_show', ['id' => $card->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }

    /**
     * @Rest\Patch(
     *     path="/cards/{id}",
     *     name="cards_edit",
     *     requirements={"id"="\d+"}
     * )
     */
    public function edit(Request $request, Card $card, SerializerInterface $serializer){

        /** @var Card $updatedCard*/
        $updatedCard = $serializer->deserialize($request->getContent(), 'App\Entity\Card', 'json');
        $card
            ->setName($updatedCard->getName())
            ->setDescription($updatedCard->getDescription())
        ;

        $this->em->flush();

        return $this->view($card, Response::HTTP_ACCEPTED, [
            'Location' => $this->generateUrl('api_cards_show', ['id' => $card->getId()]),
        ]);
    }

    /**
     * @Rest\Delete(
     *     path="/cards/{id}",
     *     name="cards_delete",
     *     requirements={"id"="\d+"}
     * )
     *
     * @Rest\View(
     *     statusCode=200
     * )
     */
    public function delete(Card $card){
        $this->em->remove($card);
        $this->em->flush();

        return new Response("The card is successfully deleted");
    }
}
