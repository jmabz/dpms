<?php

namespace App\Controller;

use App\Entity\DiagnosisCategory;
use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\Clinic;
use App\Form\DiagnosisCategoryType;
use App\Form\DoctorAccountType;
use App\Form\PatientType;
use App\Form\ClinicType;
use Symfony\Component\HttpFoundation\Request;
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
     * @Route("/admin/clinics", name="clinic_list")
     */
    public function listClinics()
    {
        $clinics = $this->getDoctrine()
            ->getRepository(Clinic::class)
            ->findAll();

        return $this->render('admin/cliniclist.html.twig', [
            'clinics' => $clinics
        ]);
    }

    /**
     * @Route("/admin/patients/{page}", name="patient_list")
     */
    public function listPatients($page = 1)
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

        return $this->render('admin/patientlist.html.twig', [
            'patients' => $patients,
            'maxPages' => $maxPages,
            'thisPage' => $thisPage,
        ]);
    }

    /**
     * @Route("/admin/doctors/{page}", name="doctor_list")
     */
    public function listDoctors($page = 1)
    {
        $doctors = $this->getDoctrine()
            ->getRepository(Doctor::class)
            ->findAllDoctorsPaged($page);

        // $totalItemsReturned = $doctors->getIterator()->count();

        $totalItems = $doctors->count();

        // $iterator = $doctors->getIterator();

        $limit = 10;
        $maxPages = ceil($totalItems / $limit);

        $thisPage = $page;

        if ($thisPage > $maxPages) {
            $thisPage = $maxPages;
        }

        return $this->render('admin/doctorlist.html.twig', [
                'doctors' => $doctors,
                'maxPages' => $maxPages,
                'thisPage' => $thisPage,
        ]);
    }

    /**
     * @Route("/admin/diagnosiscategories/{page}", name="diagnosis_categories")
     */
    public function listDiagnosisCategories($page = 1)
    {
        $diagnosisCategories = $this->getDoctrine()
            ->getRepository(DiagnosisCategory::class)
            ->findAllDiagCategoriesPaged($page);

        // $totalItemsReturned = $diagnosisCategories->getIterator()->count();

        $totalItems = $diagnosisCategories->count();

        // $iterator = $diagnosisCategories->getIterator();

        $limit = 10;
        $maxPages = ceil($totalItems / $limit);

        $thisPage = $page;

        if ($thisPage > $maxPages) {
            $thisPage = $maxPages;
        }

        return $this->render('admin/diagnosiscategories.html.twig', [
                'diagnosiscategories' => $diagnosisCategories,
                'maxPages' => $maxPages,
                'thisPage' => $thisPage,
        ]);
    }

    /**
     * @Route("/admin/adddoctor", name="add_doctor")
     */
    public function addDoctor(Request $request)
    {
        $doctor = new Doctor();
        $form = $this->createForm(DoctorAccountType::class, $doctor);
        
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctor = $form->getData();

            $password = $this->get('security.password_encoder')
                ->encodePassword($doctor, $doctor->getPlainPassword());

            $doctor->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($doctor);
            $entityManager->flush();

            return $this->redirectToRoute('doctor_list');
        }

        return $this->render('admin/adddoctor.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/addpatient", name="add_patient")
     */
    public function addPatient(Request $request)
    {
        $patient = new Patient();
        $form = $this->createForm(PatientType::class, $patient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patient = $form->getData();

            $password = $this->get('security.password_encoder')
                ->encodePassword($patient, $patient->getPlainPassword());

            $patient->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($patient);
            $entityManager->flush();

            return $this->redirectToRoute('patient_list');
        }

        return $this->render('admin/addpatient.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/adddiagnosiscategory", name="add_diagnosis_category")
     */
    public function addDiagnosisCategory(Request $request)
    {
        $diagnosisCategory = new DiagnosisCategory();
        $form = $this->createForm(DiagnosisCategoryType::class, $diagnosisCategory);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $diagnosisCategory = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($diagnosisCategory);
            $entityManager->flush();

            return $this->redirectToRoute('diagnosis_categories');
        }

        return $this->render('admin/adddiagnosiscategory.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/admin/addclinic", name="add_clinic")
     */
    public function addClinic(Request $request)
    {
        $clinic = new Clinic();
        $form = $this->createForm(
            ClinicType::class,
            $clinic
        );

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $clinic = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($clinic);
            $entityManager->flush();

            return $this->redirectToRoute('clinic_list');
        }

        return $this->render('/admin/addclinic.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
