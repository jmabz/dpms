<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Form\AppointmentType;
use App\Form\AppointmentAcceptanceType;
use App\Repository\AppointmentRepository;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\User\UserInterface;

class AppointmentController extends Controller
{
    /**
     * @Route("admin/appointments", name="appointment_index", methods="GET")
     */
    public function listAppointments(AppointmentRepository $apptRepsitory): Response
    {
        return $this->render('admin/appointmentlist.html.twig', [
            'appointments' => $apptRepsitory->findAll()
            ]);
    }

    /**
     * @Route("doctor/appointments", name="appointment_doctor", methods="GET")
     */
    public function listAssignedAppointments(UserInterface $user): Response
    {
        return $this->render('admin/appointmentlist.html.twig', [
            'appointments' => $user->getAppointments()
            ]);
    }
            
    /**
     * @Route("patient/setappointment", name="add_appointment", methods="GET|POST")
     */
    public function setAppointment(UserInterface $user, Request $request): Response
    {
        $appointment = new Appointment();
        $appointment->setPatient($user);
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($appointment);
            $entityManager->flush();

            return $this->redirectToRoute('patient');
        }

        return $this->render('patient/setappointment.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("doctor/checkappointment/{appointmentId}", name="check_appointment", methods="GET|POST")
     */
    public function checkAppointment(Request $request, AppointmentRepository $apptRepsitory, $appointmentId): Response
    {
        $appointment = $apptRepsitory->find($appointmentId);
        $form = $this->createForm(AppointmentAcceptanceType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('appointment_doctor');
        }

        return $this->render('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }
    
    /**
     * @Route("/doctor/appointments/{appointmentId}", name="appointment_show", methods="GET")
     */
    public function show(AppointmentRepository $apptRepsitory, $appointmentId): Response
    {

        return $this->render('doctor/appointment.html.twig', ['appointment' => $apptRepsitory->find($appointmentId)]);
    }

    /**
     * @Route("/patient/appointments/{appointmentId}/edit", name="appointment_edit", methods="GET|POST")
     */
    public function edit(Request $request, Appointment $appointment): Response
    {
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('appointment_edit', ['appointmentId' => $appointment->getId()]);
        }

        return $this->render('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * @Route("/patient/appointments/{appointmentId}", name="appointment_delete", methods="DELETE")
     */
    public function delete(Request $request, Appointment $appointment): Response
    {
        if ($this->isCsrfTokenValid('delete'.$appointment->getId(), $request->request->get('_token'))) {
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($appointment);
            $entityManager->flush();
        }

        return $this->redirectToRoute('appointment_index');
    }
}
