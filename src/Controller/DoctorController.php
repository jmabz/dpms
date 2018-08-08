<?php

namespace App\Controller;

use App\Entity\Appointment;
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
     * @param [type] $appointmentId
     * @return Response
     *
     * @Route("/doctor/recorddiagnosis/{appointmentId}", name="record_diagnosis")
     */
    public function recordDiagnosis(UserInterface $user, Request $request, $appointmentId) : Response
    {
        $patientRecord = new PatientRecord();
        $appointment = $this->getDoctrine()
            ->getRepository(Appointment::class)
            ->find($appointmentId);

        $patientRecord->setCheckupReason($appointment->getReason());
        $patientRecord->setCheckupDate($appointment->getAppointmentDate());
        $form = $this->createForm(PatientRecordType::class, $patientRecord);

        $form->handleRequest($request);

        $patient = $appointment->getPatient();

        $patientUserInfo = $patient->getUserInfo();

        if ($form->isSubmitted() && $form->isValid()) {
            $patientRecord = $form->getData();
            $patientRecord->setPatient($patient);
            $patientRecord->setDoctor($user);

            $appointment->setAppointmentStatus("Completed");

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appointment);
            $entityManager->flush();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($patientRecord);
            $entityManager->flush();

            return $this->redirectToRoute('appointment_doctor');
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
