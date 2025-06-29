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
    public function index(Request $request, TreatmentRepository $repo): JsonResponse
    {
        $treatmentPlan = $request->query->get('treatmentPlan');
        $startDate = $request->query->get('startDate');
        $endDate = $request->query->get('endDate');
        $diagnosisId = $request->query->getInt('diagnosis_id');
        $itemsPerPage = $request->query->getInt('itemsPerPage', 10);
        $page = $request->query->getInt('page', 1);

        $qb = $repo->createQueryBuilder('t');

        if ($treatmentPlan) {
            $qb->andWhere('t.treatmentPlan LIKE :plan')
                ->setParameter('plan', "%$treatmentPlan%");
        }
        if ($startDate) {
            $qb->andWhere('t.startDate = :start')
                ->setParameter('start', new \DateTime($startDate));
        }
        if ($endDate) {
            $qb->andWhere('t.endDate = :end')
                ->setParameter('end', new \DateTime($endDate));
        }
        if ($diagnosisId) {
            $qb->andWhere('t.diagnosis = :did')
                ->setParameter('did', $diagnosisId);
        }

        $qb->setFirstResult(($page - 1) * $itemsPerPage)
            ->setMaxResults($itemsPerPage);

        $treatments = $qb->getQuery()->getResult();

        $data = [];
        foreach ($treatments as $t) {
            $data[] = [
                'id' => $t->getId(),
                'treatmentPlan' => $t->getTreatmentPlan(),
                'startDate' => $t->getStartDate()->format('Y-m-d'),
                'endDate' => $t->getEndDate()?->format('Y-m-d'),
                'diagnosis' => $t->getDiagnosis()?->getId(),
            ];
        }

        return $this->json([
            'page' => $page,
            'itemsPerPage' => $itemsPerPage,
            'data' => $data,
        ]);
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
