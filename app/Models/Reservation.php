<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reservation extends Model
{
    protected $fillable = ['user_id', 'parking_id', 'start_time', 'end_time'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function parking()
    {
        return $this->belongsTo(Parking::class);
    }
}