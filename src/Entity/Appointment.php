<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AppointmentRepository")
 */
class Appointment
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="date")
     */
    private $appointmentDate;

    /**
     * @ORM\Column(type="string", length=15)
     */
    private $appointmentStatus;

    /**
     * @ORM\Column(type="text")
     */
    private $reason;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Doctor", inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $doctor;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\Patient", inversedBy="appointments")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    public function __construct()
    {
        $this->appointmentStatus = "Undecided";
    }

    public function getId()
    {
        return $this->id;
    }

    public function getAppointmentDate(): ?\DateTimeInterface
    {
        return $this->appointmentDate;
    }

    public function setAppointmentDate(\DateTimeInterface $appointmentDate): self
    {
        $this->appointmentDate = $appointmentDate;

        return $this;
    }

    public function getAppointmentStatus(): ?string
    {
        return $this->appointmentStatus;
    }

    public function setAppointmentStatus(string $appointmentStatus): self
    {
        $this->appointmentStatus = $appointmentStatus;

        return $this;
    }

    public function getReason(): ?string
    {
        return $this->reason;
    }

    public function setReason(string $reason): self
    {
        $this->reason = $reason;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): self
    {
        $this->patient = $patient;

        return $this;
    }
}
