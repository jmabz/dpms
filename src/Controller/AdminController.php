<?php

namespace App\Controller;

use App\Entity\DiagnosisCategory;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Form\DiagnosisCategoryType;
use App\Form\DoctorType;
use App\Form\PatientType;
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
        $entityManager = $this->getDoctrine()->getManager();

        return $this->render('admin/cliniclist.html.twig');
    }

    /**
     * @Route("/patients", name="patient_list")
     */
    public function listPatients()
    {
        $patients = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findAll();

        return $this->render('admin/patientlist.html.twig', [
            'patients' => $patients
        ]);
    }

    /**
     * @Route("/doctors", name="doctor_list")
     */
    public function listDoctors()
    {
        $doctors = $this->getDoctrine()
            ->getRepository(Doctor::class)
            ->findAll();

        return $this->render('admin/doctorlist.html.twig', [
                'doctors' => $doctors,
        ]);
    }

    /**
     * @Route("/diagnosiscategories", name="diagnosis_categories")
     */
    public function listDiagnosisCategories()
    {
        $diagnosisCategories = $this->getDoctrine()
            ->getRepository(DiagnosisCategory::class)
            ->findAll();

        return $this->render('admin/diagnosiscategories.html.twig', [
                'diagnosiscategories' => $diagnosisCategories,
        ]);
    }

    /**
     * @Route("/adddoctor", name="add_doctor")
     */
    public function addDoctor()
    {
        // TODO: place form here
        
        $doctor = new Doctor();
        $form = $this->createForm(DoctorType::class, $doctor);
        
        return $this->render('admin/adddoctor.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/addpatient", name="add_patient")
     */
    public function addPatient()
    {
        // TODO: place form here
        
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);

        return $this->render('admin/addpatient.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/adddiagnosiscategory", name="add_diagnosis_category")
     */
    public function addDiagnosisCategory()
    {
        // TODO: place form here
        
        $diagnosisCategory = new DiagnosisCategory();
        $form = $this->createForm(DiagnosisCategoryType::class, $diagnosisCategory);

        return $this->render('admin/adddiagnosiscategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
