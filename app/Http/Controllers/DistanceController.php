<?php
// app/Http/Controllers/DistanceController.php

namespace App\Http\Controllers;

use App\Services\DistanceCalculator;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class DistanceController extends Controller
{
    private $distanceCalculator;

    public function __construct(DistanceCalculator $distanceCalculator)
    {
        $this->distanceCalculator = $distanceCalculator;
    }

    /**
     * Show the distance calculator form
     */
    public function index()
    {
        return view('distance.calculator');
    }

    /**
     * Calculate distance between two points
     * 
     * @param Request $request
     * @return JsonResponse
     */
    public function calculate(Request $request): JsonResponse
    {
        $request->validate([
            'lat1' => 'required|numeric|between:-90,90',
            'lng1' => 'required|numeric|between:-180,180',
            'lat2' => 'required|numeric|between:-90,90',
            'lng2' => 'required|numeric|between:-180,180',
            'in_miles' => 'boolean'
        ]);

        $distance = $this->distanceCalculator->calculateDistance(
            $request->lat1,
            $request->lng1,
            $request->lat2,
            $request->lng2,
            $request->in_miles ?? false
        );

        if ($distance === null) {
            return response()->json([
                'error' => 'Invalid coordinates provided'
            ], 400);
        }

        return response()->json([
            'distance' => $distance,
            'unit' => $request->in_miles ? 'miles' : 'kilometers'
        ]);
    }
}