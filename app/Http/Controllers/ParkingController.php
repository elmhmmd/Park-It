<?php
namespace App\Http\Controllers;

use App\Models\Parking;
use App\Models\Reservation;
use Illuminate\Http\Request;

class ParkingController extends Controller
{
    protected $parkingModel;
    protected $reservationModel;

    public function __construct(Parking $parking, Reservation $reservation)
    {
        $this->middleware('auth:sanctum');
        $this->middleware('role:admin')->only(['store', 'update', 'destroy', 'stats']);
        $this->parkingModel = $parking;
        $this->reservationModel = $reservation;
    }

    public function search(Request $request)
    {
        $request->validate([...]); 
        $parkings = $this->parkingModel->where('city', $request->input('city'))->get();
    }

}