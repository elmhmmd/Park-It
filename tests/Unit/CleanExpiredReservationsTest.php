<?php

namespace Tests\Unit;

use App\Jobs\CleanExpiredReservations;
use App\Models\Reservation;
use Mockery;
use Tests\TestCase;

class CleanExpiredReservationsTest extends TestCase
{
    protected $reservationMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservationMock = Mockery::mock('alias:' . Reservation::class);
    }

    /** @test */
    public function it_deletes_expired_reservations()
    {
        $this->reservationMock->shouldReceive('where')
            ->with('end_time', '<', Mockery::type(\Illuminate\Support\Carbon::class))
            ->once()
            ->andReturnSelf();
        $this->reservationMock->shouldReceive('delete')
            ->once()
            ->andReturn(1);

        $job = new CleanExpiredReservations();
        $job->handle();

        $this->assertTrue(true); // If no exceptions, it worked
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}