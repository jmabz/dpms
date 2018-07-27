<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\AccreditationInfoRepository")
 */
class AccreditationInfo
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
    private $accreditationCode;

    /**
     * @ORM\Column(type="date")
     */
    private $accreditationDate;

    /**
     * @ORM\Column(type="date")
     */
    private $accreditationExpDate;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Doctor", mappedBy="accreditationInfo", cascade={"persist", "remove"})
     */
    private $doctor;

    public function getId()
    {
        return $this->id;
    }

    public function getAccreditationCode(): ?string
    {
        return $this->accreditationCode;
    }

    public function setAccreditationCode(string $accreditationCode): self
    {
        $this->accreditationCode = $accreditationCode;

        return $this;
    }

    public function getAccreditationDate(): ?\DateTimeInterface
    {
        return $this->accreditationDate;
    }

    public function setAccreditationDate(\DateTimeInterface $accreditationDate): self
    {
        $this->accreditationDate = $accreditationDate;

        return $this;
    }

    public function getAccreditationExpDate(): ?\DateTimeInterface
    {
        return $this->accreditationExpDate;
    }

    public function setAccreditationExpDate(\DateTimeInterface $accreditationExpDate): self
    {
        $this->accreditationExpDate = $accreditationExpDate;

        return $this;
    }

    // public function getDoctor(): ?User
    // {
    //     return $this->doctor;
    // }

    // public function setDoctor(?User $doctor): self
    // {
    //     $this->doctor = $doctor;

    //     // set (or unset) the owning side of the relation if necessary
    //     $newAccreditationInfo = $doctor === null ? null : $this;
    //     if ($newAccreditationInfo !== $doctor->getAccreditationInfo()) {
    //         $doctor->setAccreditationInfo($newAccreditationInfo);
    //     }

    //     return $this;
    // }
}
