<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientRepository")
 */
class Patient extends User
{

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserInfo", cascade={"persist", "remove"})
     * @Assert\Valid()
     */
    protected $userInfo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PatientRecord", cascade={"persist", "remove"}, mappedBy="patient")
     */
    private $patientRecords;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Appointment", mappedBy="patient", orphanRemoval=true)
     */
    private $appointments;

    public function __construct()
    {
        $this->patientRecords = new ArrayCollection();
        parent::setRoles(['ROLE_PATIENT']);
        $this->appointments = new ArrayCollection();
    }

    public function getPatientRecords(): Collection
    {
        return $this->patientRecords;
    }

    public function getUserInfo(): ?UserInfo
    {
        return $this->userInfo;
    }

    public function setUserInfo(?UserInfo $userInfo): self
    {
        $this->userInfo = $userInfo;

        return $this;
    }

    /**
     * @return Collection|Appointment[]
     */
    public function getAppointments(): Collection
    {
        return $this->appointments;
    }

    public function addAppointment(Appointment $appointment): self
    {
        if (!$this->appointments->contains($appointment)) {
            $this->appointments[] = $appointment;
            $appointment->setPatient($this);
        }

        return $this;
    }

    public function removeAppointment(Appointment $appointment): self
    {
        if ($this->appointments->contains($appointment)) {
            $this->appointments->removeElement($appointment);
            // set the owning side to null (unless already changed)
            if ($appointment->getPatient() === $this) {
                $appointment->setPatient(null);
            }
        }

        return $this;
    }
}
