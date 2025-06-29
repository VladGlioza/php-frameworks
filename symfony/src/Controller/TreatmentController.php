<?php

namespace App\Controller;

use App\Entity\Treatment;
use App\Repository\TreatmentRepository;
use App\Repository\DiagnosisRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/treatments')]
class TreatmentController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(TreatmentRepository $repo): JsonResponse
    {
        $treatments = $repo->findAll();
        $data = [];

        foreach ($treatments as $treatment) {
            $data[] = [
                'id' => $treatment->getId(),
                'diagnosis' => $treatment->getDiagnosis()?->getId(),
                'treatmentPlan' => $treatment->getTreatmentPlan(),
                'startDate' => $treatment->getStartDate()->format('Y-m-d'),
                'endDate' => $treatment->getEndDate()?->format('Y-m-d'),
            ];
        }

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function store(
        Request $request,
        EntityManagerInterface $em,
        DiagnosisRepository $diagnosisRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $diagnosis = $diagnosisRepo->find($data['diagnosis_id']);

        if (!$diagnosis) {
            return $this->json(['error' => 'Diagnosis not found'], 404);
        }

        $treatment = new Treatment();
        $treatment->setDiagnosis($diagnosis);
        $treatment->setTreatmentPlan($data['treatmentPlan'] ?? null);
        $treatment->setStartDate(new \DateTime($data['startDate']));
        if (isset($data['endDate'])) {
            $treatment->setEndDate(new \DateTime($data['endDate']));
        }

        $em->persist($treatment);
        $em->flush();

        return $this->json(['message' => 'Treatment created', 'id' => $treatment->getId()], 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Treatment $treatment): JsonResponse
    {
        return $this->json([
            'id' => $treatment->getId(),
            'diagnosis' => $treatment->getDiagnosis()?->getId(),
            'treatmentPlan' => $treatment->getTreatmentPlan(),
            'startDate' => $treatment->getStartDate()->format('Y-m-d'),
            'endDate' => $treatment->getEndDate()?->format('Y-m-d'),
        ]);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Request $request,
        Treatment $treatment,
        EntityManagerInterface $em,
        DiagnosisRepository $diagnosisRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (isset($data['diagnosis_id'])) {
            $diagnosis = $diagnosisRepo->find($data['diagnosis_id']);
            if ($diagnosis) {
                $treatment->setDiagnosis($diagnosis);
            }
        }

        $treatment->setTreatmentPlan($data['treatmentPlan'] ?? $treatment->getTreatmentPlan());
        if (isset($data['startDate'])) {
            $treatment->setStartDate(new \DateTime($data['startDate']));
        }
        if (isset($data['endDate'])) {
            $treatment->setEndDate(new \DateTime($data['endDate']));
        }

        $em->flush();

        return $this->json(['message' => 'Treatment updated']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Treatment $treatment, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($treatment);
        $em->flush();

        return $this->json(['message' => 'Treatment deleted']);
    }
}
