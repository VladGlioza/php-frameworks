<?php

namespace App\Controller;

use App\Entity\Appointment;
use App\Repository\AppointmentRepository;
use App\Repository\PatientRepository;
use App\Repository\DoctorRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

#[Route('/api/appointments')]
class AppointmentController extends AbstractController
{
    #[Route('', methods: ['GET'])]
    public function index(AppointmentRepository $repo): JsonResponse
    {
        $appointments = $repo->findAll();
        $data = [];

        foreach ($appointments as $app) {
            $data[] = [
                'id' => $app->getId(),
                'patient' => $app->getPatient()->getId(),
                'doctor' => $app->getDoctor()->getId(),
                'appointmentDate' => $app->getAppointmentDate()->format('Y-m-d'),
                'notes' => $app->getNotes(),
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

        $app = new Appointment();
        $app->setPatient($patient);
        $app->setDoctor($doctor);
        $app->setAppointmentDate(new \DateTime($data['appointmentDate']));
        $app->setNotes($data['notes'] ?? null);

        $em->persist($app);
        $em->flush();

        return $this->json(['message' => 'Appointment created', 'id' => $app->getId()], 201);
    }

    #[Route('/{id}', methods: ['GET'])]
    public function show(Appointment $app): JsonResponse
    {
        return $this->json([
            'id' => $app->getId(),
            'patient' => $app->getPatient()->getId(),
            'doctor' => $app->getDoctor()->getId(),
            'appointmentDate' => $app->getAppointmentDate()->format('Y-m-d'),
            'notes' => $app->getNotes(),
        ]);
    }

    #[Route('/{id}', methods: ['PUT'])]
    public function update(
        Request $request,
        Appointment $app,
        EntityManagerInterface $em,
        PatientRepository $patientRepo,
        DoctorRepository $doctorRepo
    ): JsonResponse {
        $data = json_decode($request->getContent(), true);

        if (isset($data['patient_id'])) {
            $patient = $patientRepo->find($data['patient_id']);
            if ($patient) {
                $app->setPatient($patient);
            }
        }

        if (isset($data['doctor_id'])) {
            $doctor = $doctorRepo->find($data['doctor_id']);
            if ($doctor) {
                $app->setDoctor($doctor);
            }
        }

        if (isset($data['appointmentDate'])) {
            $app->setAppointmentDate(new \DateTime($data['appointmentDate']));
        }

        $app->setNotes($data['notes'] ?? $app->getNotes());

        $em->flush();

        return $this->json(['message' => 'Appointment updated']);
    }

    #[Route('/{id}', methods: ['DELETE'])]
    public function delete(Appointment $app, EntityManagerInterface $em): JsonResponse
    {
        $em->remove($app);
        $em->flush();

        return $this->json(['message' => 'Appointment deleted']);
    }
}
