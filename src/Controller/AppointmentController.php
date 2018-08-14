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
     * Lists all appointments
     *
     * @param AppointmentRepository $apptRepsitory
     * @return Response
     *
     * @Route("admin/appointments", name="appointment_list", methods="GET")
     */
    public function listAppointments(AppointmentRepository $apptRepsitory): Response
    {
        return $this->render('admin/appointmentlist.html.twig', [
            'appointments' => $apptRepsitory->findAll()
        ]);
    }

    /**
     * Lists all appointments from the doctor or patient's side
     *
     * @param UserInterface $user
     * @return Response
     *
     * @Route("doctor/appointments", name="appointment_doctor", methods="GET")
     * @Route("patient/appointments", name="appointment_patient", methods="GET")
     */
    public function listAssignedAppointments(UserInterface $user): Response
    {
        return $this->render('admin/appointmentlist.html.twig', [
            'appointments' => $user->getAppointments()
        ]);
    }

    /**
     * Set an appointment
     *
     * @param UserInterface $user
     * @param Request $request
     * @return Response
     *
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

            $this->addFlash('success', 'Your appointment has been set!');
            $this->addFlash('error', 'Error: cant add an appointment');

            return $this->redirectToRoute('appointment_patient');
        }

        return $this->render('patient/setappointment.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Accept an appointment
     *
     * @param Request $request
     * @param AppointmentRepository $apptRepsitory
     * @param [type] $appointmentId
     * @return Response
     *
     * @Route("doctor/acceptappointment/{appointmentId}", name="accept_appointment", methods="GET|POST")
     */
    public function acceptAppointment(AppointmentRepository $apptRepsitory, $appointmentId): Response
    {
        $appointment = $apptRepsitory->find($appointmentId);
        $appointment->setAppointmentStatus("Accepted");
        $this->getDoctrine()
            ->getManager()
            ->flush();
        return $this->redirectToRoute('appointment_doctor');
    }

    /**
     * Decline an appointment
     *
     * @param Request $request
     * @param AppointmentRepository $apptRepsitory
     * @param [type] $appointmentId
     * @return Response
     *
     * @Route("doctor/declineappointment/{appointmentId}", name="decline_appointment", methods="GET|POST")
     */
    public function declineAppointment(AppointmentRepository $apptRepsitory, $appointmentId): Response
    {
        $appointment = $apptRepsitory->find($appointmentId);
        $appointment->setAppointmentStatus("Declined");
        $this->getDoctrine()
            ->getManager()
            ->flush();
        return $this->redirectToRoute('appointment_doctor');
    }

    /**
     * Display a single appointment by its ID
     *
     * @param AppointmentRepository $apptRepsitory
     * @param [type] $appointmentId
     * @return Response
     *
     * @Route("/doctor/appointments/{appointmentId}", name="appointment_show", methods="GET")
     */
    public function show(AppointmentRepository $apptRepsitory, $appointmentId): Response
    {
        return $this->render('doctor/appointment.html.twig', [
            'appointment' => $apptRepsitory->find($appointmentId)
        ]);
    }

    /**
     * Edit an appointment
     *
     * @param Request $request
     * @param Appointment $appointment
     * @return Response
     *
     * @Route("/admin/appointments/{appointmentId}/edit", name="appointment_edit", methods="GET|POST")
     */
    public function edit(Request $request, AppointmentRepository $apptRepository, $appointmentId): Response
    {
        $appointment = $apptRepository->find($appointmentId);
        $form = $this->createForm(AppointmentType::class, $appointment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $this->getDoctrine()
                ->getManager()
                ->flush();

            return $this->redirectToRoute('appointment_list');
        }

        return $this->render('appointment/edit.html.twig', [
            'appointment' => $appointment,
            'form' => $form->createView(),
        ]);
    }

    /**
     * Delete an appointment
     *
     * @param AppointmentRepository $apptRepsitory
     * @param [type] $appointmentId
     * @return Response
     *
     * @Route("/admin/appointments/{appointmentId}", name="appointment_delete", methods="DELETE")
     */
    public function delete(AppointmentRepository $apptRepository, $appointmentId): Response
    {
        $appointment = $apptRepository->find($appointmentId);

        if (!$appointment) {
            throw $this->createNotFoundException(
                'No appointment found for ID '.$appointmentId
            );
        }
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->remove($appointment);
            $entityManager->flush();

        return $this->redirectToRoute('appointment_list');
    }
}
