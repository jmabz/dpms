<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\AccreditationInfoType;
use App\Form\UserInfoType;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class ProfileController extends Controller
{
    /**
     * @Route("/userprofile/id/{userId}", name="profile")
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
            'userId' => $userId,
        ]);
    }

    /**
     * @Route("/userprofile/edit/{userId}", name="edit_profile")
     */
    public function editProfile($userId, Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);

        $userInfo = $user->getUserInfo();

        $form = $this->createForm(UserInfoType::class, $userInfo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userInfo = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userInfo);
            $entityManager->flush();

            $this->addFlash('success', 'Profile is up-to-date!');

            return $this->redirectToRoute('profile', ['userId' => $user->getId(),]);
        }

        return $this->render('profile/editprofile.html.twig', [
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/userprofile/editaccred/{userId}", name="edit_accred")
     */
    public function editAccredInfo($userId, Request $request)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);
        $accreditationInfo = $user->getAccreditationInfo();

        $form = $this->createForm(AccreditationInfoType::class, $accreditationInfo);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form-> isValid()) {
            $accreditationInfo = $form->getData();

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($accreditationInfo);
            $entityManager->flush();

            return $this->redirectToRoute('profile', ['userId' => $user->getId(),]);
        }

        return $this->render('profile/editaccredinfo.html.twig', [
            'form' => $form->createView(),
            'userId' => $userId,
        ]);
    }
}
