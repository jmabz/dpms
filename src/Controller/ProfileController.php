<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\User;
use App\Form\DoctorType;
use App\Form\PatientType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    /**
     * @Route("/profile/{userId}", name="profile")
     */
    public function viewProfile($userId)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);

        $accreditationinfo = null;
        $role = (new \ReflectionClass($user))->getShortName();

        $userinfo = $user->getUserInfo();
        if ($user instanceof Doctor) {
            $accreditationinfo = $user->getAccreditationInfo();
        }
        
        return $this->render('profile/profile.html.twig', [
            'userinfo' => $userinfo,
            'accreditationinfo' => $accreditationinfo,
            'role' => $role,
        ]);
    }

    /**
     * @Route("/userprofile/edit", name="edit_profile")
     */
    public function editProfile(Request $request)
    {
        $userId = 1;
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);

        $form = $this->createForm($user instanceof Doctor ? DoctorType::class : PatientType::class, $user);
        
        $form->handleRequest($request);

        $userinfo = $user->getUserInfo();

        if ($form->isSubmitted() && $form->isValid()) {
            $user = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($doctor);
            $entityManager->flush();

            return $this->redirectToRoute('profile', ['userId' => $userId,]);
        }


        return $this->render('profile/editprofile.html.twig', [
            'form' => $form->createView(),
            'accreditationinfo' => ($user instanceof Doctor)
        ]);
    }
}
