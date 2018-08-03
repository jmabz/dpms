<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass="App\Repository\ClinicRepository")
 */
class Clinic
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @Assert\NotBlank(message="Please enter the clinic's name.")
     * @ORM\Column(type="string", length=255)
     */
    private $clinicName;

    /**
     * @Assert\NotBlank(message="Please enter the clinic's email address.")
     * @ORM\Column(type="string", length=255)
     */
    private $email;

    /**
     * @Assert\NotBlank()
     * @Assert\GreaterThanOrEqual("6:00")
     * @Assert\LessThan("17:00")
     * @ORM\Column(type="time")
     */
    private $schedStart;

    /**
     * @Assert\NotBlank()
     * @ORM\Column(type="time")
     */
    private $schedEnd;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\Doctor", inversedBy="clinics", cascade={"persist"})
     */
    private $doctors;

    public function __construct()
    {
        $this->doctors = new ArrayCollection();
    }

    public function getId()
    {
        return $this->id;
    }

    public function getClinicName(): ?string
    {
        return $this->clinicName;
    }

    public function setClinicName(string $clinicName): self
    {
        $this->clinicName = $clinicName;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getSchedStart(): ?\DateTimeInterface
    {
        return $this->schedStart;
    }

    public function setSchedStart(\DateTimeInterface $schedStart): self
    {
        $this->schedStart = $schedStart;

        return $this;
    }

    public function getSchedEnd(): ?\DateTimeInterface
    {
        return $this->schedEnd;
    }

    public function setSchedEnd(\DateTimeInterface $schedEnd): self
    {
        $this->schedEnd = $schedEnd;

        return $this;
    }

    /**
     * @return Collection|Doctors[]
     */
    public function getDoctors(): Collection
    {
        return $this->doctors;
    }

    // public function setDoctor(?Doctor $doctor): self
    // {
    //     $this->doctor = $doctor;

    //     return $this;
    // }
    public function addDoctor(Doctor $doctor): self
    {
        if (!$this->doctors->contains($doctor)) {
            $this->doctors[] = $doctor;
        }

        return $this;
    }

    public function removeDoctor(Doctor $doctor): self
    {
        if ($this->doctors->contains($doctor)) {
            $this->doctors->removeElement($doctor);
        }

        return $this;
    }
}
