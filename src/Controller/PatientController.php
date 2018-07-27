<?php

namespace App\Controller;

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
     * @Route("patient/records", name="record_history")
     */
    public function viewRecordHistory()
    {
        return $this->render('patient/recordhistory.html.twig');
    }

    /**
     * @Route("patient/records/{recordId}", name="view_record")
     */
    public function viewPatientRecord($recordId)
    {
        $record = 'record';
        return $this->render('patient/patientrecord.html.twig', [
            'record' => $record,
        ]);
    }
}
