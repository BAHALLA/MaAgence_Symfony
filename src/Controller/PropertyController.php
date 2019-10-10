<?php

namespace App\Controller;
use App\Entity\Property;
use App\Repository\PropertyRepository;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class PropertyController extends AbstractController {


    private $propertyRepository;
    private $objectManager;
    public function __construct(PropertyRepository $propertyRepository, ObjectManager $objectManager)
    {
        $this->propertyRepository = $propertyRepository;
        $this->objectManager = $objectManager;
    }

    /**
     * @Route("/biens", name="property.index")
     * @return Response
     */
    public function index() : Response {

            $prop = $this->propertyRepository->findAll();
            $prop[0]->setSold(false);
            dump($prop);
            $this->objectManager->flush();
            return $this->render("property/index.html.twig"
            , [
                'current_menu' => "properties"
                ]);
    }

    /**
     * @Route( "/biens/{slug}-{id}", name="property.show", requirements={ "slug" : "[a-z0-9\-]*"})
     * @return Response
     */
    public function show($slug, $id) :Response {
            $property = $this->propertyRepository->find($id);

            if($property->getTitleSlug() !== $slug) {
               return $this->redirectToRoute("property.show", [
                    'id' => $property->getId(),
                    'slug' => $property->getTitleSlug()
                ],
                    301);
            }
            return $this->render("/property/show.html.twig",
                [
                    'property' => $property,
                    'current_menu' => "properties"
                ]
                );
    }
}