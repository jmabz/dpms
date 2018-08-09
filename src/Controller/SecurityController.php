<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\ChangePasswordType;
use App\Form\Model\ChangePassword;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\User\UserInterface;

class SecurityController extends Controller
{
    /**
     * @Route("/security", name="security")
     */
    public function index()
    {
        return $this->render('security/index.html.twig', [
            'controller_name' => 'SecurityController',
        ]);
    }

    /**
     * @Route("/login", name="login")
     */
    public function login()
    {
        $authenticationUtils = $this->get('security.authentication_utils');

        $error = $authenticationUtils->getLastAuthenticationError();
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', array(
            'last_username' => $lastUsername,
            'error' => $error,
        ));
    }

    /**
     * @Route("/register", name="register")
     */
    public function register()
    {
        return $this->render('security/register.html.twig');
    }

    /**
     * Reset a user's password based on ID
     *
     * @return void
     * @Route("/admin/resetpassword/{userId}", name="reset_password")
     */
    public function resetPassword($userId)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);

        $password = $this->get('security.password_encoder')
            ->encodePassword($user, 1);

        $user->setPassword($password);

        $entityManager = $this->getDoctrine()->getManager();
        $entityManager->persist($user);
        $entityManager->flush();

        return ($user instanceof Doctor) ? $this->redirectToRoute('doctor_list') : $this->redirectToRoute('patient_list');
    }

   /**
     * Change a user's password
     *
     * @return void
     * @Route("/changepassword/", name="change_password")
     */
    public function changePassword(UserInterface $user, Request $request)
    {
        $changePasswordModel = new ChangePassword();
        $form = $this->createForm(ChangePasswordType::class, $changePasswordModel);
        $form->handleRequest($request);
        $userToChangePass = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($user->getId());
        if ($form->isSubmitted() && $form->isValid()) {
            $password = $this->get('security.password_encoder')
                ->encodePassword($userToChangePass, $form->get('newPassword')->getData());

            $user->setPassword($password);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($user);
            $entityManager->flush();

            return $this->redirectToRoute('profile', [
            'userId' => $user->getId(),
            ]);
        }

        return $this->render('security/changepassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}
