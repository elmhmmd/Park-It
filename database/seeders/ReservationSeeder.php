<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parking;
use App\Models\User;
use App\Models\Reservation;

class ReservationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $user = User::where('email', 'user@email.com')->firstOrFail();
        $parking = Parking::where('name', 'Downtown Lot')->firstOrFail();

        Reservation::create([
            'user_id' => $user->id,
            'parking_id' => $parking->id,
            'start_time' => now()->addHours(1),
            'end_time' => now()->addHours(2),
        ]);
    }
}
