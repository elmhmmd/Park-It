<?php

namespace Tests\Unit;

use App\Jobs\CleanExpiredReservations;
use App\Models\Reservation;
use Mockery;
use Tests\TestCase;

class CleanExpiredReservationsTest extends TestCase
{
    public function test_it_deletes_expired_reservations()
    {
        // Create a mock for the Reservation model
        $reservationMock = Mockery::mock('alias:App\Models\Reservation');

        // Mock the query behavior
        $reservationMock->shouldReceive('where')
            ->with('end_time', '<', Mockery::type(\Illuminate\Support\Carbon::class))
            ->once()
            ->andReturnSelf();

        $reservationMock->shouldReceive('delete')
            ->once()
            ->andReturn(1); // Simulating deletion

        // Run the job
        (new CleanExpiredReservations())->handle();

        $this->assertTrue(true); // Test passes if no exceptions are thrown
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}