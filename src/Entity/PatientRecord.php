<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\PatientRecordRepository")
 */
class PatientRecord
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
    private $checkupDate;

    /**
     * @ORM\Column(type="text")
     */
    private $checkupReason;

    /**
     * @ORM\Column(type="text")
     */
    private $diagnosis;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\DiagnosisCategory", inversedBy="patientRecords")
     * @ORM\JoinColumn(nullable=false)
     */
    private $diagnosisCategory;

    /**
     * @ORM\Column(type="decimal", precision=10, scale=2)
     */
    private $payment;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="patientRecords")
     * @ORM\JoinColumn(nullable=false)
     */
    private $patient;

    /**
     * @ORM\ManyToOne(targetEntity="App\Entity\User", inversedBy="patientRecords")
     * @ORM\JoinColumn(nullable=false)
     */
    private $doctor;

    public function getId()
    {
        return $this->id;
    }

    public function getCheckupDate(): ?\DateTimeInterface
    {
        return $this->checkupDate;
    }

    public function setCheckupDate(\DateTimeInterface $checkupDate): self
    {
        $this->checkupDate = $checkupDate;

        return $this;
    }

    public function getCheckupReason(): ?string
    {
        return $this->checkupReason;
    }

    public function setCheckupReason(string $checkupReason): self
    {
        $this->checkupReason = $checkupReason;

        return $this;
    }

    public function getDiagnosis(): ?string
    {
        return $this->diagnosis;
    }

    public function setDiagnosis(string $diagnosis): self
    {
        $this->diagnosis = $diagnosis;

        return $this;
    }

    public function getDiagnosisCategory(): ?DiagnosisCategory
    {
        return $this->diagnosisCategory;
    }

    public function setDiagnosisCategory(?DiagnosisCategory $diagnosisCategory): self
    {
        $this->diagnosisCategory = $diagnosisCategory;

        return $this;
    }

    public function getPayment()
    {
        return $this->payment;
    }

    public function setPayment($payment): self
    {
        $this->payment = $payment;

        return $this;
    }

    public function getPatient(): ?User
    {
        return $this->patient;
    }

    public function setPatient(?User $patient): self
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDoctor(): ?User
    {
        return $this->doctor;
    }

    public function setDoctor(?User $doctor): self
    {
        $this->doctor = $doctor;

        return $this;
    }
}
