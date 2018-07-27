<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DoctorController extends Controller
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

}
