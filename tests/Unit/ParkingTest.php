<?php

namespace Tests\Unit;

use App\Models\Parking;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Mockery;
use Tests\TestCase;

class ParkingTest extends TestCase
{
    public function test_reservations_returns_has_many_relation()
    {
        $parking = Mockery::mock(Parking::class)->makePartial();
        $relationMock = Mockery::mock(HasMany::class);
        $relationMock->shouldReceive('getForeignKeyName')->andReturn('parking_id');

        $parking->shouldReceive('reservations')->andReturn($relationMock);

        $relation = $parking->reservations();

        $this->assertInstanceOf(HasMany::class, $relation);
        $this->assertEquals('parking_id', $relation->getForeignKeyName());
    }

    public function test_available_spaces_calculates_correctly()
    {
        $startTime = now();
        $endTime = now()->addHour();

        $parking = Mockery::mock(Parking::class)->makePartial();
        $parking->shouldReceive('getAttribute')->with('total_spaces')->andReturn(5);

        $queryMock = Mockery::mock();
        $queryMock->shouldReceive('where')->with(Mockery::type('Closure'))->andReturnSelf();
        $queryMock->shouldReceive('count')->andReturn(2);

        $parking->shouldReceive('reservations')->andReturn($queryMock);

        $this->assertEquals(3, $parking->availableSpaces($startTime, $endTime));
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}