<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Response;

final class PropertyController extends AbstractController
{
    private const PROPERTIES = [
        [
            'id' => 1,
            'title' => '1-кімнатна квартира',
            'description' => 'Опис1',
            'price_per_day' => 800,
            'location' => 'Київ'
        ],
        [
            'id' => 2,
            'title' => 'Офісне приміщення',
            'description' => 'Опис2',
            'price_per_day' => 1500,
            'location' => 'Житомир'
        ],
    ];

    #[Route('/properties', name: 'get_properties', methods: [Request::METHOD_GET])]
    public function getProperties(): JsonResponse
    {
        return new JsonResponse([
            'data' => self::PROPERTIES
        ], Response::HTTP_OK);
    }

    #[Route('/properties/{id}', name: 'get_property', methods: [Request::METHOD_GET])]
    public function getProperty(string $id): JsonResponse
    {
        $property = $this->getPropertyById(self::PROPERTIES, $id);

        if (!$property) {
            return new JsonResponse([
                'data' => ['error' => 'Property not found with id ' . $id]
            ], Response::HTTP_NOT_FOUND);
        }

        return new JsonResponse(['data' => $property], Response::HTTP_OK);
    }

    #[Route('/properties', name: 'post_property', methods: [Request::METHOD_POST])]
    public function createProperty(Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        $propertyId = random_int(100, 999);

        $newProperty = [
            'id' => $propertyId,
            'title' => $requestData['title'] ?? 'Без назви',
            'description' => $requestData['description'] ?? '',
            'price_per_day' => $requestData['price_per_day'] ?? 0,
            'location' => $requestData['location'] ?? ''
        ];

        // create

        return new JsonResponse(['data' => $newProperty], Response::HTTP_CREATED);
    }

    #[Route('/properties/{id}', name: 'put_property', methods: [Request::METHOD_PUT])]
    public function updateProperty(string $id, Request $request): JsonResponse
    {
        $requestData = json_decode($request->getContent(), true);

        // update

        return new JsonResponse([
            'data' => [
                'id' => $id,
                'updated' => $requestData
            ]
        ], Response::HTTP_OK);
    }

    #[Route('/properties/{id}', name: 'delete_property', methods: [Request::METHOD_DELETE])]
    public function deleteProperty(string $id): JsonResponse
    {
        // delete

        return new JsonResponse([
            'data' => 'Property deleted with id ' . $id
        ], Response::HTTP_OK);
    }

    private function getPropertyById(array $properties, int $id): ?array
    {
        foreach ($properties as $property) {
            if ($property['id'] == $id) {
                return $property;
            }
        }
        return null;
    }
}
