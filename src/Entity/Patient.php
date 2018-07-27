<?php

namespace App\Entity;

use App\Entity\User;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 */
class Patient extends User
{

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\UserInfo", cascade={"persist", "remove"})
     */
    protected $userInfo;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PatientRecord", mappedBy="patient")
     */
    private $patientRecords;

    public function __construct()
    {
        $this->patientRecords = new ArrayCollection();
        parent::setRole(['ROLE_PATIENT']);
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
}
