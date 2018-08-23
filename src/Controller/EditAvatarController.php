<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\User;
use App\Entity\UserInfo;
use App\Form\AccreditationInfoType;
use App\Form\UserInfoType;
use App\Service\FileUploader;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;

class EditAvatarController extends Controller
{
    /**
     * @Route("/userprofile/edit-avatar/{userId}", name="edit_avatar")
     */
    public function editAvatar($userId, Request $request, FileUploader $fileUploader)
    {
        $user = $this->getDoctrine()
            ->getRepository(User::class)
            ->find($userId);

        $userInfo = $user->getUserInfo();

        $form = $this->createForm(UserInfoType::class, $userInfo, ['account' => false]);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $userInfo = $form->getData();

            $file = $userInfo->getFileUpload();
            $fileName = $fileUploader->upload($file);
            $userInfo->setAvatar($fileName);

            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($userInfo);
            $entityManager->flush();

            $this->addFlash('success', 'Profile Updated!');

            return $this->redirectToRoute('profile', ['userId' => $user->getId(),]);
        }

        return $this->render('profile/editavatar.html.twig', [
            'form' => $form->createView(),
            'userId' => $userId,
        ]);
    }
}