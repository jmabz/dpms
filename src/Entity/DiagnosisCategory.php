<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\DiagnosisCategoryRepository")
 */
class DiagnosisCategory
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $diagnosisName;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\PatientRecord", mappedBy="diagnosisCategory")
     */
    private $patientRecords;

    public function __construct()
    {
        $this->patientRecords = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDiagnosisName(): ?string
    {
        return $this->diagnosisName;
    }

    public function setDiagnosisName(string $diagnosisName): self
    {
        $this->diagnosisName = $diagnosisName;

        return $this;
    }

    /**
     * @return Collection|PatientRecord[]
     */
    public function getPatientRecords(): Collection
    {
        return $this->patientRecords;
    }

    public function addPatientRecord(PatientRecord $patientRecord): self
    {
        if (!$this->patientRecords->contains($patientRecord)) {
            $this->patientRecords[] = $patientRecord;
            $patientRecord->setDiagnosisCategory($this);
        }

        return $this;
    }

    public function removePatientRecord(PatientRecord $patientRecord): self
    {
        if ($this->patientRecords->contains($patientRecord)) {
            $this->patientRecords->removeElement($patientRecord);
            // set the owning side to null (unless already changed)
            if ($patientRecord->getDiagnosisCategory() === $this) {
                $patientRecord->setDiagnosisCategory(null);
            }
        }

        return $this;
    }
}
