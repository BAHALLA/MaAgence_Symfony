<?php
namespace App\Controller\Admin;

use App\Entity\Property;
use App\Form\PropertyFormType;
use App\Repository\PropertyRepository;

use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController {

    private $propertyRepository;
    /**
     * @var ObjectManager
     */
    private $objectManager;

    /**
     * AdminPropertyController constructor.
     * @param PropertyRepository $propertyRepository
     * @param ObjectManager $objectManager
     */
    public function __construct(PropertyRepository $propertyRepository, ObjectManager $objectManager)
    {
        $this->propertyRepository = $propertyRepository;
        $this->objectManager = $objectManager;
    }

    /**
     * @Route("/admin", name="admin.property.index")
     * @return \Symfony\Component\HttpFoundation\Response
     */

    public function index(): Response {
        $properties = $this->propertyRepository->findAll();

        return $this->render('admin/index.html.twig', compact('properties'));
    }

    /**
     * @Route("/admin/property/create", name="admin.property.new")
     * @param Request $request
     * @return Response
     */
    public function add(Request $request): Response {
        $property = new Property();
        $form = $this->createForm(PropertyFormType::class, $property);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
            $this->objectManager->persist($property);
            $this->addFlash('success', 'Ajout avec succee');
            $this->objectManager->flush();
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->render("/admin/new.html.twig",[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.edit", methods="GET|POST")
     * @param $id
     * @param Request $request
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit($id, Request $request) :Response {
        $property = $this->propertyRepository->find($id);
        $form = $this->createForm(PropertyFormType::class, $property);

        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()) {
                $this->objectManager->flush();
                $this->addFlash('success', 'Mise a jour avec succee');
                return $this->redirectToRoute('admin.property.index');
        }
        return $this->render("/admin/edit.html.twig",[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/admin/property/{id}", name="admin.property.delete", methods="DELETE")
     * @param Property $property
     * @param Request $request
     * @return Response
     */
    public function delete(Property $property, Request $request) :Response{

        if($this->isCsrfTokenValid('delete'.$property->getId(),$request->get('_token')))
        {
            $this->objectManager->remove($property);
            $this->objectManager->flush();
            $this->addFlash('success', 'Suppression avec succee');
            return $this->redirectToRoute('admin.property.index');
        }
        return $this->redirectToRoute('admin.property.index');
    }



}
