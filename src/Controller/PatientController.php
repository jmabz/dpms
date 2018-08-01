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
     * @Route("patient/records/{page}", name="record_history")
     */
    public function listRecordHistory($page = 1)
    {
        // TODO: query active patient's ID
        $patientId = 2;
        $records = $this->getDoctrine()
            ->getRepository(PatientRecord::class)
            ->findAllPatientRecordsPaged($patientId, $page);

        $totalItems = $records->count();

        // $iterator = $records->getIterator();

        $limit = 10;
        $maxPages = ceil($totalItems / $limit);

        $thisPage = $page;

        if ($thisPage > $maxPages) {
            $thisPage = $maxPages;
        }

        return $this->render('patient/recordhistory.html.twig', [
            'records' => $records,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage,
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
