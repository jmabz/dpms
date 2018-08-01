<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DoctorRepository")
 */
class Doctor extends User
{

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserInfo", cascade={"persist", "remove"})
     */
    protected $userInfo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PatientRecord", mappedBy="doctor")
     */
    private $patientRecords;
    
    /**
     * @ORM\OneToOne(targetEntity="App\Entity\AccreditationInfo", inversedBy="doctor", cascade={"persist", "remove"})
     */
    private $accreditationInfo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Clinic", mappedBy="doctor")
     */
    private $clinics;
    
    public function __construct()
    {
        $this->patientRecords = new ArrayCollection();
        $this->clinics = new ArrayCollection();
        parent::setRoles(['ROLE_DOCTOR']);
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

    public function getPatientRecords(): Collection
    {
        return $this->patientRecords;
    }
    public function addPatientRecord(PatientRecord $patientRecord): self
    {
        if (!$this->patientRecords->contains($patientRecord)) {
            $this->patientRecords[] = $patientRecord;
            $patientRecord->setPatient($this);
        }

        return $this;
    }

    public function removePatientRecord(PatientRecord $patientRecord): self
    {
        if ($this->patientRecords->contains($patientRecord)) {
            $this->patientRecords->removeElement($patientRecord);
            // set the owning side to null (unless already changed)
            if ($patientRecord->getPatient() === $this) {
                $patientRecord->setPatient(null);
            }
        }

        return $this;
    }

    public function getAccreditationInfo(): ?AccreditationInfo
    {
        return $this->accreditationInfo;
    }

    public function setAccreditationInfo(?AccreditationInfo $accreditationInfo): self
    {
        $this->accreditationInfo = $accreditationInfo;

        return $this;
    }

    public function getClinics(): Collection
    {
        return $this->clinics;
    }

    public function addClinic(Clinic $clinic): self
    {
        if (!$this->clinics->contains($clinic)) {
            $this->clinics[] = $clinic;
            $clinic->addDoctor($this);
        }

        return $this;
    }

    public function removeClinic(Clinic $clinic): self
    {
        if ($this->clinics->contains($clinic)) {
            $this->clinics->removeElement($clinic);
            $clinic->removeDoctor($this);
        }

        return $this;
    }
}
