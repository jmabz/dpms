<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\PatientRecord;
use App\Form\PatientRecordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DoctorController extends Controller
{
    /**
     * Displays all patients for diagnosis
     *
     * @param integer $page
     * @return Response
     *
     * @Route("/doctor/patients", name="patient_list_doctors")
     */
    public function listPatientsForDiagnosis($page = 1) : Response
    {
        $patients = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findAllPatientsPaged($page);

        // $totalItemsReturned = $patients->getIterator()->count();

        $totalItems = $patients->count();

        // $iterator = $patients->getIterator();

        $limit = 10;
        $maxPages = ceil($totalItems / $limit);

        $thisPage = $page;

        if ($thisPage > $maxPages) {
            $thisPage = $maxPages;
        }

        return $this->render(
            'admin/patientlist.html.twig',
            [
            'patients' => $patients,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage,
            ]
        );
    }

    /**
     * Records a patient's diagnosis
     *
     * @param UserInterface $user
     * @param Request $request
     * @param [type] $patientId
     * @return Response
     *
     * @Route("/doctor/recorddiagnosis/{patientId}", name="record_diagnosis")
     */
    public function recordDiagnosis(UserInterface $user, Request $request, $patientId) : Response
    {
        $patientRecord = new PatientRecord();
        $form = $this->createForm(PatientRecordType::class, $patientRecord);

        $form->handleRequest($request);

        $patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($patientId);

        $patientUserInfo = $patient->getUserInfo();

        if ($form->isSubmitted() && $form->isValid()) {
            $patientRecord = $form->getData();
            $patientRecord->setPatient($patient);
            $patientRecord->setDoctor($user);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($patientRecord);
            $entityManager->flush();

            return $this->redirectToRoute('patient_list_doctors');
        }

        return $this->render(
            'doctor/recorddiagnosis.html.twig',
            [
            'form' => $form->createView(),
            'patient' => $patientUserInfo,
            ]
        );
    }
    /**
     * @Route("/messages", name="send_message")
     */
    public function message()
    {

        return $this->render('message/message.html.twig');
    }
}
