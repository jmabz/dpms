<?php

namespace App\Controller;

use App\Entity\PatientRecord;
use App\Form\PatientRecordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DoctorController extends Controller
{
    /**
     * @Route("/recorddiagnosis", name="record_diagnosis")
     */
    public function recordDiagnosis(Request $request)
    {
        $patientRecord = new PatientRecord();
        $form = $this->createForm(PatientRecordType::class, $patientRecord);

        $form->handleRequest($request);

        //TODO: query doctor and patient's ID, to be passed to the record

        if ($form->isSubmitted() && $form->isValid()) {
            $patientRecord = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($patientRecord);
            $entityManager->flush();
        }

        return $this->render('doctor/recorddiagnosis.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
