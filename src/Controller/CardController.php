<?php

namespace App\Controller;

use App\Entity\Card;
use App\Repository\CardRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Rest\Route("/api", name="api_")
 */
class CardController extends AbstractFOSRestController
{
    private $em;
    private $serializer;

    public function __construct(EntityManagerInterface $em, SerializerInterface $serializer)
    {
        $this->em = $em;
        $this->serializer = $serializer;
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
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $cards = $cardRepository->findAll();
        $data = $this->serializer->serialize($cards, 'json', SerializationContext::create()->setGroups(['cards']));

        $response->setContent($data);

        return $response;
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
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $data = $this->serializer->serialize($card, 'json', SerializationContext::create()->setGroups(['card']));
        $response->setContent($data);

        return $response;
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

        return new Response(['message' => 'The card is successfully deleted']);
    }
}
