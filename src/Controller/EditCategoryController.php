<?php

namespace App\Controller;

use App\Entity\DiagnosisCategory;
use App\Form\DiagnosisCategoryType;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class EditCategoryController extends Controller
{	
	 /**
     * Displays a form to edit an existing user entity.
     *
     * @Route("/admin/edit-category/{id}", name="user_edit")
     * @Method({"GET", "POST"})
     */
    public function editAction(Request $request, $id)
    {
        $dc = $this->getDoctrine()
            ->getRepository(DiagnosisCategory::class)
            ->find($id);
        $form = $this->createForm(DiagnosisCategoryType::class, $dc);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {

            $dc = $form->getData();
            
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($dc);
            $entityManager->flush();

            return $this->redirectToRoute('diagnosis_categories');
        }

        return $this->render('admin/edit_category.html.twig', array(
            'form' => $form->createView(),
        ));
    }

}
