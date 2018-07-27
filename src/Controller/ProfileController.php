<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class ProfileController extends Controller
{
    /**
     * @Route("/recorddiagnosis", name="recorddiagnosis")
     */
    public function index()
    {
        return $this->render('doctor/recorddiagnosis.html.twig', [
            'controller_name' => 'DoctorController',
        ]);
    }

    /**
     * @Route("/userprofile", name="profile")
     */
    public function viewprofile()
    {
    	return $this->render('profile/profile.html.twig');
    }
}
