<?php

namespace Tests\Unit;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mockery;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    public function test_parking_returns_belongs_to_relation()
    {
        $reservation = Mockery::mock(Reservation::class)->makePartial();
        $relationMock = Mockery::mock(BelongsTo::class);
        $relationMock->shouldReceive('getForeignKeyName')->andReturn('parking_id');

        $reservation->shouldReceive('parking')->andReturn($relationMock);

        $relation = $reservation->parking();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('parking_id', $relation->getForeignKeyName());
    }

    public function test_is_expired_checks_end_time()
    {
        $reservation = Mockery::mock(Reservation::class)->makePartial();
        $reservation->shouldReceive('isExpired')->andReturn(true);

        $this->assertTrue($reservation->isExpired());
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}