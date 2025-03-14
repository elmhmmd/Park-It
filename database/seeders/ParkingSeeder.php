<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Parking;

class ParkingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Parking::create(['name' => 'Downtown Lot', 'city' => 'Paris', 'total_spaces' => 5]);
        Parking::create(['name' => 'Suburb Lot', 'city' => 'Paris', 'total_spaces' => 3]);
        Parking::create(['name' => 'City Center', 'city' => 'Lyon', 'total_spaces' => 4]);
    }
}
