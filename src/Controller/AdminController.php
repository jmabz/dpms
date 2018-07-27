<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class AdminController extends Controller
{
    /**
     * @Route("/admin", name="admin")
     */
    public function index()
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    /**
     * @Route("/clinics", name="clinic_list")
     */
    public function listClinics()
    {
        return $this->render('admin/cliniclist.html.twig');
    }

    /**
     * @Route("/patients", name="patient_list")
     */
    public function listPatients()
    {
        return $this->render('admin/patientlist.html.twig');
    }

    /**
     * @Route("/doctors", name="doctor_list")
     */
    public function listDoctors()
    {
        return $this->render('admin/doctorlist.html.twig');
    }


    /**
     * @Route("/diagnosiscategories", name="diagnosis_categories")
     */
    public function listDiagnosisCategories()
    {
        return $this->render('admin/diagnosiscategories.html.twig');
    }

    /**
     * @Route("/adddoctor", name="add_doctor")
     */
    public function addDoctor()
    {
        // TODO: place form here
        return $this->render('admin/adddoctor.html.twig');
    }

    /**
     * @Route("/addpatient", name="add_patient")
     */
    public function addPatient()
    {
        // TODO: place form here
        return $this->render('admin/addpatient.html.twig');
    }

    /**
     * @Route("/adddiagnosiscategory", name="add_diagnosis_category")
     */
    public function addDiagnosisCategory()
    {
        // TODO: place form here
        return $this->render('admin/adddiagnosiscategory.html.twig');
    }
}
