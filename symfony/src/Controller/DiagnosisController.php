<?php

namespace App\Controller;

use App\Entity\Diagnosis;
use App\Repository\DiagnosisRepository;
use App\Repository\PatientRepository;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/diagnoses')]
class DiagnosisController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(DiagnosisRepository $repo): JsonResponse
    {
        $diagnoses = $repo->findAll();
        $data = [];

        foreach ($diagnoses as $diag) {
            $data[] = [
                'id' => $diag->getId(),
                'patient' => $diag->getPatient()->getId(),
                'doctor' => $diag->getDoctor()->getId(),
                'diagnosisText' => $diag->getDiagnosisText(),
                'diagnosisDate' => $diag->getDiagnosisDate()->format('Y-m-d'),
            ];
        }

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function store(
        Request $request,
        EntityManagerInterface $em,
        PatientRepository $patientRepo,
        DoctorRepository $doctorRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        $patient = $patientRepo->find($data['patient_id']);
        $doctor = $doctorRepo->find($data['doctor_id']);

        if (!$patient || !$doctor) {
            return $this->json(['error' => 'Patient or doctor not found'], 404);
        }

        $diag = new Diagnosis();
        $diag->setPatient($patient);
        $diag->setDoctor($doctor);
        $diag->setDiagnosisText($data['diagnosisText'] ?? null);
        $diag->setDiagnosisDate(new \DateTime($data['diagnosisDate']));

        $em->persist($diag);
        $em->flush();

        return $this->json(['message' => 'Diagnosis created', 'id' => $diag->getId()], 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Diagnosis $diag): JsonResponse
    {
        return $this->json([
            'id' => $diag->getId(),
            'patient' => $diag->getPatient()->getId(),
            'doctor' => $diag->getDoctor()->getId(),
            'diagnosisText' => $diag->getDiagnosisText(),
            'diagnosisDate' => $diag->getDiagnosisDate()->format('Y-m-d'),
        ]);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Request $request,
        Diagnosis $diag,
        EntityManagerInterface $em,
        PatientRepository $patientRepo,
        DoctorRepository $doctorRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (isset($data['patient_id'])) {
            $patient = $patientRepo->find($data['patient_id']);
            if ($patient) {
                $diag->setPatient($patient);
            }
        }

        if (isset($data['doctor_id'])) {
            $doctor = $doctorRepo->find($data['doctor_id']);
            if ($doctor) {
                $diag->setDoctor($doctor);
            }
        }

        $diag->setDiagnosisText($data['diagnosisText'] ?? $diag->getDiagnosisText());
        if (isset($data['diagnosisDate'])) {
            $diag->setDiagnosisDate(new \DateTime($data['diagnosisDate']));
        }

        $em->flush();

        return $this->json(['message' => 'Diagnosis updated']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Diagnosis $diag, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($diag);
        $em->flush();

        return $this->json(['message' => 'Diagnosis deleted']);
    }
}
