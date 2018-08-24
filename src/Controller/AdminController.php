<?php

namespace App\Controller;

use App\Entity\DiagnosisCategory;
use App\Entity\User;
use App\Entity\UserInfo;
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
use App\Service\FileUploader;

use Faker\Provider\en_US\Person;
use Faker\Provider\en_US\Address;
use Faker\Provider\DateTime;


class AdminController extends Controller
{

    /**
     * @Route("/admin/clinics", name="clinic_list")
     */
    public function listClinics()
    {
        $clinics = $this->getDoctrine()
            ->getRepository(Clinic::class)
            ->findAll();

        return $this->render('admin/cliniclist.html.twig', [
            'clinics' => $clinics,
        ]);
    }

    /**
     * @Route("/admin/patients/", name="patient_list")
     */
    public function listPatients()
    {
        $patients = $this->getDoctrine()
            ->getRepository(Patient::class)
            ->findAll();

        return $this->render('admin/patientlist.html.twig', [
            'patients' => $patients,
        ]);
    }

    /**
     * @Route("/admin/doctors/", name="doctor_list")
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
     * @Route("/admin/diagnosiscategories/", name="diagnosis_categories")
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
     * @Route("/admin/adddoctor", name="add_doctor")
     */
    public function addDoctor(Request $request, FileUploader $fileUploader)
    {
        $doctor = new Doctor();
        $gender = "Male";
        $userInfo = new UserInfo();
        $userInfo->setGender($gender);
        $userInfo->setFname(Person::firstNameMale());
        $userInfo->setMname(Person::lastName());
        $userInfo->setLname(Person::lastName());

        $userInfo->setBirthDate(DateTime::dateTimeBetween($startDate = '-40 years', $endDate = '-20 years'));
        $doctor->setUserInfo($userInfo);

        $form = $this->createForm(DoctorAccountType::class, $doctor);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $doctor = $form->getData();
            $file = $userInfo->getFileUpload();
            $fileName = $fileUploader->upload($file);
            $doctor->getUserInfo()->setAvatar($fileName);

            $password = $this->get('security.password_encoder')
                ->encodePassword($doctor, $doctor->getPlainPassword());

            $doctor->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($doctor);
            $entityManager->flush();

            $this->addFlash('success', 'Profile has been successfully saved!');

            return $this->redirectToRoute('doctor_list');
        }

        return $this->render('admin/adddoctor.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Adds a new patient account
     *
     * @param Request $request
     * @param FileUploader $fileUploader
     * @return void
     *
     * @Route("/admin/addpatient", name="add_patient")
     */
    public function addPatient(Request $request, FileUploader $fileUploader)
    {
        $patient = new Patient();
        $gender = "Male";
        $userInfo = new UserInfo();
        $userInfo->setGender($gender);
        $userInfo->setFname(Person::firstNameMale());
        $userInfo->setMname(Person::lastName());
        $userInfo->setLname(Person::lastName());

        $userInfo->setBirthDate(DateTime::dateTimeBetween($startDate = '-40 years', $endDate = '-20 years'));
        $patient->setUserInfo($userInfo);

        $form = $this->createForm(PatientType::class, $patient);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $patient = $form->getData();

            $file = $userInfo->getFileUpload();
            $fileName = $fileUploader->upload($file);
            $patient->getUserInfo()->setAvatar($fileName);

            $password = $this->get('security.password_encoder')
                ->encodePassword($patient, $patient->getPlainPassword());

            $patient->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($patient);
            $entityManager->flush();

            $this->addFlash('success', 'Profile has been successfully saved!');

            return $this->redirectToRoute('patient_list');
        }

        return $this->render('admin/addpatient.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * Deletes a user
     *
     * @param [type] $userId
     * @return void
     *
     * @Route("/admin/deleteuser/{userId}", name="delete_user")
     */
    public function deleteUser($userId)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        $role = (new \ReflectionClass($user))->getShortName();

        if (!$user) {
            throw $this->createNotFoundException(
                'No user found for ID '.$userId
            );
        }
        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->remove($user);
        $entityManager->flush();

        return 'Doctor' == $role ? $this->redirectToRoute('doctor_list') : $this->redirectToRoute('patient_list');
    }

    /**
     * Adds a diagnosis category
     *
     * @param Request $request
     * @return void
     *
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
     * Adds a clinic
     *
     * @param Request $request
     * @return void
     *
     * @Route("/admin/addclinic", name="add_clinic")
     */
    public function addClinic(Request $request)
    {
        $clinic = new Clinic();
        $form = $this->createForm(
            ClinicType::class,
            $clinic,
            [
                'clinicId' => 0,
                'addMode' => true,
            ]
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

    /**
     * @Route("/admin/editclinic/{clinicId}", name="edit_clinic")
     */
    public function editClinic(Request $request, $clinicId)
    {
        $clinic = $this->getDoctrine()
            ->getRepository(Clinic::class)
            ->find($clinicId);

        $form = $this->createForm(
            ClinicType::class,
            $clinic,
            [
                'clinicId' => 0,
                'addMode' => false,
                'editClinic' => true,
            ]
        );

        $form->handleRequest($request);
        if (!$clinic) {
            throw $this->createNotFoundException(
                'No clinic found for ID '.$clinicId
            );
        }
        if ($form->isSubmitted() && $form->isValid()) {
            $clinic = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($clinic);
            $entityManager->flush();

            return $this->redirectToRoute('view_clinic', [
                'clinicId' => $clinicId,
            ]);
        }

        return $this->render('/admin/editclinic.html.twig', [
            'form' => $form->createView(),
            'clinic' => $clinic,
        ]);
    }

    /**
     * @Route("/admin/clinic/{clinicId}/adddoctors", name="add_doctors_to_clinic")
     */
    public function addDoctorsToClinic(Request $request, $clinicId)
    {
        $clinic = $this->getDoctrine()
            ->getRepository(Clinic::class)
            ->find($clinicId);
        $form = $this->createForm(
            ClinicType::class,
            $clinic,
            [
                'clinicId' => $clinicId,
                'addMode' => true,
                'editDoctors' => true,
            ]
        );

        $form->handleRequest($request);

        if ($form->isValid() && $form->isSubmitted()) {
            // $clinic = $form->getData();
            $doctorsToAdd = $form->get('doctors')->getData();
            foreach ($doctorsToAdd as $doctor) {
                $clinic->addDoctor($doctor);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($clinic);
            $entityManager->flush();

            return $this->redirectToRoute('view_clinic', ['clinicId' => $clinicId]);
        }

        return $this->render('/admin/adddoctorstoclinic.html.twig', [
            'form' => $form->createView(),
            'clinic' => $clinic,
            'addDoctors' => true,
        ]);
    }

    /**
     * @Route("/admin/clinic/{clinicId}/removedoctors", name="remove_doctors_from_clinic")
     */
    public function removeDoctorsFromClinic(Request $request, $clinicId)
    {
        $clinic = $this->getDoctrine()
            ->getRepository(Clinic::class)
            ->find($clinicId);
        $form = $this->createForm(
            ClinicType::class,
            $clinic,
            [
                'clinicId' => $clinicId,
                'addMode' => false,
                'editDoctors' => true,
            ]
        );

        $form->handleRequest($request);

        if ($form->isSubmitted()) {
            // $clinic = $form->getData();
            $doctorsToAdd = $form->get('doctors')->getData();
            foreach ($doctorsToAdd as $doctor) {
                $clinic->removeDoctor($doctor);
            }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($clinic);
            $entityManager->flush();

            return $this->redirectToRoute('view_clinic', ['clinicId' => $clinicId]);
        }

        return $this->render('/admin/adddoctorstoclinic.html.twig', [
            'form' => $form->createView(),
            'clinic' => $clinic,
            'addDoctors' => false,
        ]);
    }
}
