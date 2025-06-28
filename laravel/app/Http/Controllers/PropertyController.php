<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class PropertyController extends Controller
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

    public function index(): JsonResponse
    {
        return response()->json(['data' => self::PROPERTIES], 200);
    }

    public function show($id): JsonResponse
    {
        $property = collect(self::PROPERTIES)->firstWhere('id', $id);

        if (!$property) {
            return response()->json(['data' => ['error' => 'Property not found with id ' . $id]], 404);
        }

        return response()->json(['data' => $property], 200);
    }

    public function store(Request $request): JsonResponse
    {
        $propertyId = rand(100, 999);

        $newProperty = [
            'id' => $propertyId,
            'title' => $request->input('title', 'Без назви'),
            'description' => $request->input('description', ''),
            'price_per_day' => $request->input('price_per_day', 0),
            'location' => $request->input('location', '')
        ];

        // store

        return response()->json(['data' => $newProperty], 201);
    }

    public function update(Request $request, $id): JsonResponse
    {
        // update

        return response()->json([
            'data' => [
                'id' => $id,
                'updated' => $request->all()
            ]
        ], 200);
    }

    public function destroy($id): JsonResponse
    {
        // delete

        return response()->json(['data' => 'Property deleted with id ' . $id], 200);
    }
}
