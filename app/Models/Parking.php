<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Parking extends Model
{
    protected $fillable = ['name', 'city', 'total_spaces'];

    public function reservations()
    {
        return $this->hasMany(Reservation::class);
    }

    public function availableSpaces($startTime, $endTime)
    {
        $reserved = $this->reservations()
            ->where(function ($query) use ($startTime, $endTime) {
                $query->whereBetween('start_time', [$startTime, $endTime])
                      ->orWhereBetween('end_time', [$startTime, $endTime])
                      ->orWhere(function ($q) use ($startTime, $endTime) {
                          $q->where('start_time', '<=', $startTime)
                            ->where('end_time', '>=', $endTime);
                      });
            })->count();

        return $this->total_spaces - $reserved;
    }
}