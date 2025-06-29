<?php

namespace App\Controller;

use App\Entity\Doctor;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/doctors')]
class DoctorController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(DoctorRepository $repo): JsonResponse
    {
        $doctors = $repo->findAll();
        $data = [];

        foreach ($doctors as $doctor) {
            $data[] = [
                'id' => $doctor->getId(),
                'name' => $doctor->getName(),
                'specialty' => $doctor->getSpecialty(),
                'phone' => $doctor->getPhone(),
                'email' => $doctor->getEmail(),
            ];
        }

        return $this->json($data);
    }

    #[Route('', methods: ['POST'])]
    public function store(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $doctor = new Doctor();
        $doctor->setName($data['name']);
        $doctor->setSpecialty($data['specialty']);
        $doctor->setPhone($data['phone'] ?? null);
        $doctor->setEmail($data['email'] ?? null);

        $em->persist($doctor);
        $em->flush();

        return $this->json([
            'message' => 'Doctor created',
            'id' => $doctor->getId()
        ], 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Doctor $doctor): JsonResponse
    {
        return $this->json([
            'id' => $doctor->getId(),
            'name' => $doctor->getName(),
            'specialty' => $doctor->getSpecialty(),
            'phone' => $doctor->getPhone(),
            'email' => $doctor->getEmail(),
        ]);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(Request $request, Doctor $doctor, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        $doctor->setName($data['name'] ?? $doctor->getName());
        $doctor->setSpecialty($data['specialty'] ?? $doctor->getSpecialty());
        $doctor->setPhone($data['phone'] ?? $doctor->getPhone());
        $doctor->setEmail($data['email'] ?? $doctor->getEmail());

        $em->flush();

        return $this->json(['message' => 'Doctor updated']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Doctor $doctor, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($doctor);
        $em->flush();

        return $this->json(['message' => 'Doctor deleted']);
    }
}
