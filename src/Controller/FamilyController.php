<?php

namespace App\Controller;

use App\Entity\Family;
use App\Repository\FamilyRepository;
use Doctrine\ORM\EntityManagerInterface;
use FOS\RestBundle\Controller\AbstractFOSRestController;
use FOS\RestBundle\Controller\Annotations as Rest;
use JMS\Serializer\SerializerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @Rest\Route("/api", name="api_")
 */
class FamilyController extends AbstractFOSRestController
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
     *     path="/families",
     *     name="families_index"
     * )
     */
    public function index(FamilyRepository $familyRepository)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $families = $familyRepository->findAll();

        $data = $this->serializer->serialize($families, 'json');

        $response->setContent($data);

        return $response;
    }

    /**
     * @Rest\Get(
     *     path="/families/{id}",
     *     name="families_show",
     *     requirements={"id"="\d+"}
     * )
     */
    public function show(Family $family)
    {
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $data = $this->serializer->serialize($family, 'json');

        $response->setContent($data);

        return $response;
    }

    /**
     * @Rest\Post(
     *     path="/families",
     *     name="families_add"
     * )
     */
    public function add(Request $request){
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $data = $request->getContent();
        /**@var Family $family */
        $family = $this->serializer->deserialize($data, Family::class, 'json');

        $this->em->persist($family);
        $this->em->flush();

        return $this->view($family, Response::HTTP_CREATED, [
            'Location' => $this->generateUrl('api_families_show', ['id' => $family->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }

    /**
     * @Rest\Patch(
     *     path="/families/{id}",
     *     name="families_edit",
     *     requirements={"id"="\d+"}
     * )
     */
    public function edit(Family $family, Request $request){
        $response = new Response();
        $response->headers->set('Content-Type', 'application/json');

        $data = $request->getContent();
        /**@var Family $updatedFamily */
        $updatedFamily = $this->serializer->deserialize($data, Family::class, 'json');

        $family->setName($updatedFamily->getName());

        $this->em->flush();

        return $this->view($family, Response::HTTP_ACCEPTED, [
            'Location' => $this->generateUrl('api_families_show', ['id' => $family->getId()], UrlGeneratorInterface::ABSOLUTE_URL),
        ]);
    }


    /**
     * @Rest\Delete(
     *     path="/families/{id}",
     *     name="/families_delete",
     *     requirements={"id"="\d+"}
     * )
     */
    public function delete(Family $family){
        $this->em->remove($family);
        $this->em->flush();

        return $this->view(['message' => 'The family is successfully deleted'],  Response::HTTP_OK);
    }
}
