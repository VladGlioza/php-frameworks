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
    public function index(Request $request, PatientRepository $repo): JsonResponse
    {
        $name = $request->query->get('name');
        $gender = $request->query->get('gender');
        $dateOfBirth = $request->query->get('dateOfBirth');
        $contactInfo = $request->query->get('contactInfo');

        $itemsPerPage = $request->query->getInt('itemsPerPage', 10);
        $page = $request->query->getInt('page', 1);

        $qb = $repo->createQueryBuilder('p');

        if ($name) {
            $qb->andWhere('p.name LIKE :name')
                ->setParameter('name', '%' . $name . '%');
        }
        if ($gender) {
            $qb->andWhere('p.gender = :gender')
                ->setParameter('gender', $gender);
        }
        if ($dateOfBirth) {
            $qb->andWhere('p.dateOfBirth = :dob')
                ->setParameter('dob', new \DateTime($dateOfBirth));
        }
        if ($contactInfo) {
            $qb->andWhere('p.contactInfo LIKE :contact')
                ->setParameter('contact', '%' . $contactInfo . '%');
        }

        $qb->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage);

        $patients = $qb->getQuery()->getResult();

        $data = [];
        foreach ($patients as $p) {
            $data[] = [
                'id' => $p->getId(),
                'name' => $p->getName(),
                'gender' => $p->getGender(),
                'dateOfBirth' => $p->getDateOfBirth()->format('Y-m-d'),
                'contactInfo' => $p->getContactInfo(),
            ];
        }

        return $this->json([
            'page' => $page,
            'itemsPerPage' => $itemsPerPage,
            'data' => $data,
        ]);
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