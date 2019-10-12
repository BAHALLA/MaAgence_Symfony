<?php
namespace App\Controller\Admin;

use App\Form\PropertyFormType;
use App\Repository\PropertyRepository;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AdminPropertyController extends AbstractController {

    private $propertyRepository;
    public function __construct(PropertyRepository $propertyRepository)
    {
        $this->propertyRepository = $propertyRepository;
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
     * @Route("/admin/{id}", name="admin.property.edit")
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function edit($id) :Response {
        $property = $this->propertyRepository->find($id);
        $form = $this->createForm(PropertyFormType::class, $property);

        return $this->render("/admin/edit.html.twig",[
            'property' => $property,
            'form' => $form->createView()
        ]);
    }

}
