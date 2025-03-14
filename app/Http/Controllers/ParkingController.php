<?php

namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Reservation;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;

class ParkingController extends Controller
{
    protected $parkingModel;
    protected $reservationModel;

    public function __construct(Parking $parking, Reservation $reservation)
    {
        $this->parkingModel = $parking;
        $this->reservationModel = $reservation;
    }

    public function search(Request $request)
{
    $request->validate([
        'city' => 'required|string',
        'start_time' => 'required|date',
        'end_time' => 'required|date|after:start_time'
    ]);

    $parkings = $this->parkingModel->where('city', $request->city)
        ->get()
        ->map(function ($parking) use ($request) {
            $available = $parking->availableSpaces($request->start_time, $request->end_time);
            return $available > 0 ? [
                'id' => $parking->id,
                'name' => $parking->name,
                'city' => $parking->city,
                'total_spaces' => $parking->total_spaces,
                'available_spaces' => $available
            ] : null;
        })->filter()->values();

    return response()->json(['data' => $parkings]);
}

    /**
     * Reserve a parking spot.
     */
    public function reserve(Request $request): JsonResponse
{
    // Validate input
    $request->validate([
        'parking_id' => 'required|exists:parkings,id',
        'start_time' => 'required|date|after:now',
        'end_time' => 'required|date|after:start_time',
    ]);

    // Check authentication
    if (!$request->user()) {
        return response()->json(['error' => 'Unauthenticated'], 401);
    }

    // Find parking
    $parking = $this->parkingModel->findOrFail($request->input('parking_id'));

    // Check available spaces
    $available = $parking->availableSpaces($request->input('start_time'), $request->input('end_time'));
    \Log::info('Available spaces', ['count' => $available]);
    if ($available < 1) {
        return response()->json(['error' => 'No available spaces'], 400);
    }

    // Log request data
    \Log::info('Request data', $request->all());

    // Create reservation
    try {
        $reservation = $this->reservationModel->create([
            'user_id' => $request->user()->id,
            'parking_id' => $request->input('parking_id'),
            'start_time' => $request->input('start_time'),
            'end_time' => $request->input('end_time'),
        ]);

        \Log::info('Reservation created', ['reservation' => $reservation->toArray()]);
    } catch (\Exception $e) {
        \Log::error('Reservation creation failed', [
            'error' => $e->getMessage(),
            'trace' => $e->getTraceAsString()
        ]);
        return response()->json(['error' => 'Failed to create reservation', 'details' => $e->getMessage()], 500);
    }

    // Return success response
    return new JsonResponse(['data' => $reservation], 201);
}

    /**
     * Update an existing reservation.
     */
    public function updateReservation(Request $request, $id)
{
    $reservation = $this->reservationModel->findOrFail($id);
    if ($reservation->user_id !== auth()->id()) {
        return response()->json(['error' => 'Unauthorized'], 403);
    }

    $request->validate([
        'start_time' => 'required|date|after:now',
        'end_time' => 'required|date|after:start_time'
    ]);

    $conflicts = $this->reservationModel->where('parking_id', $reservation->parking_id)
        ->where('id', '!=', $id)
        ->where(function ($query) use ($request) {
            $query->whereBetween('start_time', [$request->start_time, $request->end_time])
                  ->orWhereBetween('end_time', [$request->start_time, $request->end_time]);
        })->exists();

    if ($conflicts) {
        return response()->json(['error' => 'Time conflict'], 400);
    }

    $reservation->update($request->only('start_time', 'end_time'));
    return response()->json($reservation);
}
    /**
     * Create a new parking lot (admin only).
     */
    public function store(Request $request): JsonResponse
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'city' => 'required|string|max:255',
            'total_spaces' => 'required|integer|min:1',
        ]);

        $parking = $this->parkingModel->create($request->only('name', 'city', 'total_spaces'));

        return response()->json(['data' => $parking], 201);
    }

    /**
     * Update an existing parking lot (admin only).
     */
    public function update(Request $request, $id): JsonResponse
    {
        $request->validate([
            'name' => 'sometimes|string|max:255',
            'city' => 'sometimes|string|max:255',
            'total_spaces' => 'sometimes|integer|min:1',
        ]);

        $parking = $this->parkingModel->findOrFail($id);
        $parking->update($request->only('name', 'city', 'total_spaces'));

        return response()->json(['data' => $parking], 200);
    }

    /**
     * Delete a parking lot (admin only).
     */
    public function destroy(Request $request, $id): JsonResponse
    {
        $parking = $this->parkingModel->findOrFail($id);
        $parking->delete();

        return response()->json(['message' => 'Parking deleted'], 200);
    }

    /**
     * Get parking statistics (admin only).
     */
    public function stats(Request $request): JsonResponse
    {
        $parkings = $this->parkingModel->withCount('reservations')->get()
            ->map(function ($parking) {
                return [
                    'name' => $parking->name,
                    'city' => $parking->city,
                    'total_spaces' => $parking->total_spaces,
                    'reservation_count' => $parking->reservations_count,
                ];
            });

        return response()->json(['data' => $parkings], 200);
    }

    public function myReservations()
{
    $reservations = $this->reservationModel->where('user_id', auth()->id())->get();
    return response()->json(['data' => $reservations]);
}
}