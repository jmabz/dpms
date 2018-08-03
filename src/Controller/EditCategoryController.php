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
    public function editAction(Request $request, DiagnosisCategory $user)
    {
        $deleteForm = $this->createDeleteForm($user);
        $editForm = $this->createForm('App\Form\DiagnosisCategoryType', $user);
        $editForm->handleRequest($request);

        if ($editForm->isSubmitted() && $editForm->isValid()) {
            $this->getDoctrine()->getManager()->flush();

            return $this->redirectToRoute('user_edit', array('id' => $user->getId()));
        }

        return $this->render('admin/edit_category.html.twig', array(
            'user' => $user,
            'edit_form' => $editForm->createView(),
            'delete_form' => $deleteForm->createView(),
        ));
    }

    /**
     * Deletes a user entity.
     *
     * @Route("/admin/{id}", name="user_delete")
     * @Method("DELETE")
     */
    public function deleteAction(Request $request, DiagnosisCategory $user)
    {
        $form = $this->createDeleteForm($user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em = $this->getDoctrine()->getManager();
            $em->remove($user);
            $em->flush();
        }

        return $this->redirectToRoute('diagnosis_categories');
    }

    /**
     * Creates a form to delete a user entity.
     *
     * @param User $user The user entity
     *
     * @return \Symfony\Component\Form\Form The form
     */
    private function createDeleteForm(DiagnosisCategory $user)
    {
        return $this->createFormBuilder()
            ->setAction($this->generateUrl('user_delete', array('id' => $user->getId())))
            ->setMethod('DELETE')
            ->getForm()
        ;
    }
}
