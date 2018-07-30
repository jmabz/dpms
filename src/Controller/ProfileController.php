<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Entity\Patient;
use App\Entity\User;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    /**
     * @Route("/userprofile/{userId}", name="profile")
     */
    public function viewprofile($userId)
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
}
