<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\PatientRecord;
use App\Form\PatientRecordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DoctorController extends Controller
{
    /**
     * @Route("/recorddiagnosis/{patientId}", name="record_diagnosis")
     */
    public function recordDiagnosis(Request $request, $patientId)
    {
        $patientRecord = new PatientRecord();
        $form = $this->createForm(PatientRecordType::class, $patientRecord);

        $form->handleRequest($request);

        //TODO: query doctor and patient's ID, to be passed to the record
        
        $doctor = $this->getDoctrine()
            ->getRepository(Doctor::class)
            ->find(1);

        $patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($patientId);

        $patientUserInfo = $patient->getUserInfo();

        if ($form->isSubmitted() && $form->isValid()) {
            $patientRecord = $form->getData();
            $patientRecord->setPatient($patient);
            $patientRecord->setDoctor($doctor);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($patientRecord);
            $entityManager->flush();
            return $this->redirectToRoute('patient_list');
        }

        return $this->render('doctor/recorddiagnosis.html.twig', [
            'form' => $form->createView(),
            'patient' => $patientUserInfo,
        ]);
    }
}
