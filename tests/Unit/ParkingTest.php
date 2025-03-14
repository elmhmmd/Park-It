<?php

namespace Tests\Unit;

use App\Models\Parking;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mockery;
use Tests\TestCase;

class ParkingTest extends TestCase
{
    /** @test */
    public function reservations_returns_has_many_relation()
    {
        $parking = new Parking();
        $relation = $parking->reservations();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('parking_id', $relation->getForeignKeyName());
    }

    /** @test */
    public function available_spaces_calculates_correctly()
    {
        $startTime = now();
        $endTime = now()->addHour();

        $parking = Mockery::mock(Parking::class)->makePartial();
        $parking->shouldReceive('getAttribute')->with('total_spaces')->andReturn(5);

        $queryMock = Mockery::mock();
        $queryMock->shouldReceive('whereBetween')
            ->with('start_time', [$startTime, $endTime])
            ->andReturnSelf();
        $queryMock->shouldReceive('orWhereBetween')
            ->with('end_time', [$startTime, $endTime])
            ->andReturnSelf();
        $queryMock->shouldReceive('orWhere')->andReturnSelf();
        $queryMock->shouldReceive('where')->with('start_time', '<=', $startTime)->andReturnSelf();
        $queryMock->shouldReceive('where')->with('end_time', '>=', $endTime)->andReturnSelf();
        $queryMock->shouldReceive('count')->andReturn(2);

        $parking->shouldReceive('reservations')->andReturn($queryMock);

        $available = $parking->availableSpaces($startTime, $endTime);

        $this->assertEquals(3, $available); // 5 total - 2 reserved = 3 available
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}