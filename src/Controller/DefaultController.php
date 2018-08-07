<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class DefaultController extends Controller
{
    /**
     * @Route("/", name="home")
     */
    public function index()
    {
        return $this->render('default/index.html.twig');
    }

    /**
     * @Route("/admin", name="admin")
     */
    public function adminIndex()
    {
        return $this->render('admin/index.html.twig');
    }

    /**
     * @Route("/doctor", name="doctor")
     */
    public function doctorIndex()
    {
        return $this->render('doctor/index.html.twig');
    }

    /**
     * @Route("/patient", name="patient")
     */
    public function patientIndex()
    {
        return $this->render('patient/index.html.twig');
    }
}
