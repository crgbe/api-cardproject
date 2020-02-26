<?php

namespace App\Controller;

use App\Entity\CardGroup;
use App\Repository\CardGroupRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use FOS\RestBundle\View\View;
use JMS\Serializer\SerializationContext;
use JMS\Serializer\SerializerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Rest\Route("/api", name="api_")
 */
class CardGroupController extends AbstractFOSRestController
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
     *     path="/card-groups",
     *     name="card_groups"
     * )
     * @param CardGroupRepository $cardGroupRepository
     * @return Response
     */
    public function index(CardGroupRepository $cardGroupRepository)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $cardGroups = $cardGroupRepository->findAll();
        $data = $this->serializer->serialize($cardGroups, 'json', SerializationContext::create()->setGroups(['card_groups']));

        $response->setContent($data);

        return $response;
    }

    /**
     * @Rest\Get(
     *     path="/card-groups/{id}",
     *     name="card_groups_show",
     *     requirements={"id"="\d+"}
     * )
     * @Rest\View()
     * @param CardGroup $cardGroup
     * @return Response
     */
    public function show(CardGroup $cardGroup){
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $data = $this->serializer->serialize($cardGroup, 'json', SerializationContext::create()->setGroups(['card_group']));
        $response->setContent($data);

        return $response;
    }

    /**
     * @Rest\Post(
     *     path="/card-groups",
     *     name="card_groups_add"
     * )
     * @ParamConverter("cardGroup", converter="fos_rest.request_body")
     * @param CardGroup $cardGroup
     * @return View
     */
    public function add(CardGroup $cardGroup){
        $this->em->persist($cardGroup);
        $this->em->flush();

        return $this->view($cardGroup, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('api_card_groups_show', ['id' => $cardGroup->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }

    /**
     * @Rest\Patch(
     *     path="/card-groups/{id}",
     *     name="card_groups_edit",
     *     requirements={"id"="\d+"}
     * )
     * @param Request $request
     * @param CardGroup $cardGroup
     * @param SerializerInterface $serializer
     * @return View
     */
    public function edit(Request $request, CardGroup $cardGroup, SerializerInterface $serializer){

        /** @var CardGroup $updatedCardGroup*/
        $updatedCardGroup = $serializer->deserialize($request->getContent(), 'App\Entity\CardGroup', 'json');
        $cardGroup->setName($updatedCardGroup->getName());

        $this->em->flush();

        return $this->view($cardGroup, Response::HTTP_ACCEPTED, [
            'Location' => $this->generateUrl('api_card_groups_show', ['id' => $cardGroup->getId()]),
        ]);
    }

    /**
     * @Rest\Delete(
     *     path="/card-groups/{id}",
     *     name="card_groups_delete",
     *     requirements={"id"="\d+"}
     * )
     * @param CardGroup $cardGroup
     * @return View
     */
    public function delete(CardGroup $cardGroup){
        $this->em->remove($cardGroup);
        $this->em->flush();

        return $this->view(["message" => "The card is successfully deleted"], Response::HTTP_OK);
    }
}
