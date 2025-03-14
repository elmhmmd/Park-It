<?php

namespace Tests\Unit;

use App\Models\Reservation;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Mockery;
use Tests\TestCase;

class ReservationTest extends TestCase
{
    protected $reservationMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->reservationMock = Mockery::mock(Reservation::class)->makePartial();
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }

    public function test_user_returns_belongs_to_relation()
    {
        $relation = $this->reservationMock->user();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('user_id', $relation->getForeignKeyName());
    }

    public function test_parking_returns_belongs_to_relation()
    {
        $relation = $this->reservationMock->parking();

        $this->assertInstanceOf(BelongsTo::class, $relation);
        $this->assertEquals('parking_id', $relation->getForeignKeyName());
    }
}