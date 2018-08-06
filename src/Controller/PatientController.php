<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\PatientRecord;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class PatientController extends Controller
{
    /**
     * @Route("/patient", name="patient")
     */
    public function index()
    {
        return $this->render('patient/index.html.twig', [
            'controller_name' => 'PatientController',
        ]);
    }

    /**
     * @Route("patient/records/", name="record_history")
     */
    public function listRecordHistory(UserInterface $user)
    {
        $records = $user->getPatientRecords();

        return $this->render('patient/recordhistory.html.twig', [
            'records' => $records,
        ]);
    }

    /**
     * @Route("patient/record/{recordId}", name="view_record")
     */
    public function viewPatientRecord($recordId)
    {
        $record = $this->getDoctrine()
            ->getRepository(PatientRecord::class)
            ->find($recordId);

        $doctor = $record->getDoctor();

        $patient = $record->getPatient();

        return $this->render('patient/patientrecord.html.twig', [
            'record' => $record,
            'doctor' => $doctor,
            'patient' => $patient,
        ]);
    }
}
