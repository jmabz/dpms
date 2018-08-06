<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\PatientRecord;
use App\Form\PatientRecordType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DoctorController extends Controller
{
    /**
     * @Route("/doctor", name="doctor")
     */
    public function index()
    {
        return $this->render(
            'doctor/index.html.twig',
            [
            'controller_name' => 'DoctorController',
            ]
        );
    }

    /**
     * @Route("/doctor/patients", name="patient_list_doctors")
     */
    public function listPatientsForDiagnosis($page = 1)
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
     * @Route("/doctor/recorddiagnosis/{patientId}", name="record_diagnosis")
     */
    public function recordDiagnosis(UserInterface $user, Request $request, $patientId)
    {
        $patientRecord = new PatientRecord();
        $form = $this->createForm(PatientRecordType::class, $patientRecord);

        $form->handleRequest($request);

        $doctor = $this->getDoctrine()
            ->getRepository(Doctor::class)
            ->find($user->getId());

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
}
