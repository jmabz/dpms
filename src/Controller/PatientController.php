<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Entity\PatientRecord;
use Symfony\Component\Routing\Annotation\Route;
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
     * @Route("patient/{patientId}/records", name="record_history")
     */
    public function listRecordHistory($patientId)
    {
        $patient = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->find($patientId);

        $records = $patient->getPatientRecords();

        return $this->render('patient/recordhistory.html.twig', [
            'records' => $records,
        ]);
    }

    /**
     * @Route("patient/records/{recordId}", name="view_record")
     */
    public function viewPatientRecord($recordId)
    {
        $record = $this->getDoctrine()
            ->getRepository(PatientRecord::class)
            ->find($recordId);

        $doctor = $record->getDoctor()->getUserInfo();

        return $this->render('patient/patientrecord.html.twig', [
            'record' => $record,
            'doctor' => $doctor,
        ]);
    }
}
