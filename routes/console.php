<?php

use App\Jobs\CleanExpiredReservations;
use Illuminate\Support\Facades\Schedule;

Schedule::job(new CleanExpiredReservations)->everyMinute();