<?php

namespace App\Controller;

use App\Entity\Patient;
use App\Repository\PatientRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/patients')]
class PatientController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(PatientRepository $patientRepository): JsonResponse
    {
        $patients = $patientRepository->findAll();
        $data = [];

        foreach ($patients as $patient) {
            $data[] = [
                'id' => $patient->getId(),
                'name' => $patient->getName(),
                'dateOfBirth' => $patient->getDateOfBirth()->format('Y-m-d'),
                'gender' => $patient->getGender(),
                'contactInfo' => $patient->getContactInfo(),
            ];
        }

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $patient = new Patient();
        $patient->setName($data['name']);
        $patient->setGender($data['gender']);
        $patient->setDateOfBirth(new \DateTime($data['dateOfBirth']));
        $patient->setContactInfo($data['contactInfo']);

        $em->persist($patient);
        $em->flush();

        return $this->json([
            'message' => 'Patient created',
            'id' => $patient->getId()
        ], 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Patient $patient): JsonResponse
    {
        return $this->json([
            'id' => $patient->getId(),
            'name' => $patient->getName(),
            'dateOfBirth' => $patient->getDateOfBirth()->format('Y-m-d'),
            'gender' => $patient->getGender(),
            'contactInfo' => $patient->getContactInfo(),
        ]);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Patient $patient, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $patient->setName($data['name'] ?? $patient->getName());
        $patient->setGender($data['gender'] ?? $patient->getGender());
        $patient->setContactInfo($data['contactInfo'] ?? $patient->getContactInfo());
        if (isset($data['dateOfBirth'])) {
            $patient->setDateOfBirth(new \DateTime($data['dateOfBirth']));
        }

        $em->flush();

        return $this->json(['message' => 'Patient updated']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Patient $patient, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($patient);
        $em->flush();

        return $this->json(['message' => 'Patient deleted']);
    }
}