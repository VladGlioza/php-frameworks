<?php

namespace App\Entity;

use App\Repository\TreatmentRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TreatmentRepository::class)]
class Treatment
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne]
    private ?Diagnosis $diagnosis = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $treatmentPlan = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTime $startDate = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTime $endDate = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getDiagnosis(): ?Diagnosis
    {
        return $this->diagnosis;
    }

    public function setDiagnosis(?Diagnosis $diagnosis): static
    {
        $this->diagnosis = $diagnosis;

        return $this;
    }

    public function getTreatmentPlan(): ?string
    {
        return $this->treatmentPlan;
    }

    public function setTreatmentPlan(?string $treatmentPlan): static
    {
        $this->treatmentPlan = $treatmentPlan;

        return $this;
    }

    public function getStartDate(): ?\DateTime
    {
        return $this->startDate;
    }

    public function setStartDate(\DateTime $startDate): static
    {
        $this->startDate = $startDate;

        return $this;
    }

    public function getEndDate(): ?\DateTime
    {
        return $this->endDate;
    }

    public function setEndDate(?\DateTime $endDate): static
    {
        $this->endDate = $endDate;

        return $this;
    }
}
