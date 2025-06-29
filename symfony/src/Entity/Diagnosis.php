<?php

namespace App\Entity;

use App\Repository\DiagnosisRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DiagnosisRepository::class)]
class Diagnosis
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'diagnoses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Patient $patient = null;

    #[ORM\ManyToOne(inversedBy: 'diagnoses')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Doctor $doctor = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $diagnosisText = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $diagnosisDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPatient(): ?Patient
    {
        return $this->patient;
    }

    public function setPatient(?Patient $patient): static
    {
        $this->patient = $patient;

        return $this;
    }

    public function getDoctor(): ?Doctor
    {
        return $this->doctor;
    }

    public function setDoctor(?Doctor $doctor): static
    {
        $this->doctor = $doctor;

        return $this;
    }

    public function getDiagnosisText(): ?string
    {
        return $this->diagnosisText;
    }

    public function setDiagnosisText(?string $diagnosisText): static
    {
        $this->diagnosisText = $diagnosisText;

        return $this;
    }

    public function getDiagnosisDate(): ?\DateTime
    {
        return $this->diagnosisDate;
    }

    public function setDiagnosisDate(\DateTime $diagnosisDate): static
    {
        $this->diagnosisDate = $diagnosisDate;

        return $this;
    }
}
