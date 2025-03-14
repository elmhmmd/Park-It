<?php

namespace Tests\Unit;

use App\Http\Controllers\ParkingController;
use App\Models\Parking;
use Illuminate\Http\Request;
use Mockery;
use Tests\TestCase;

class ParkingControllerTest extends TestCase
{
    protected $parkingMock;
    protected $requestMock;

    protected function setUp(): void
    {
        parent::setUp();
        $this->parkingMock = Mockery::mock('alias:' . Parking::class);
        $this->requestMock = Mockery::mock(Request::class);
    }

    /** @test */
    public function search_returns_available_parkings()
    {
        $this->requestMock->shouldReceive('validate')->once()->andReturn(true);
        $this->requestMock->shouldReceive('input')->with('city')->andReturn('Paris');
        $this->requestMock->shouldReceive('input')->with('start_time')->andReturn('2025-03-14 10:00:00');
        $this->requestMock->shouldReceive('input')->with('end_time')->andReturn('2025-03-14 11:00:00');
        $this->requestMock->shouldReceive('all')->andReturn([]);

        $this->parkingMock->shouldReceive('where')->with('city', 'Paris')->andReturnSelf();
        $this->parkingMock->shouldReceive('get')->andReturn(collect([
            Mockery::mock(Parking::class, [
                'id' => 1,
                'name' => 'Lot 1',
                'city' => 'Paris',
                'total_spaces' => 5,
                'availableSpaces' => fn ($start, $end) => 3,
            ]),
        ]));

        $controller = new ParkingController();
        $response = $controller->search($this->requestMock);

        $this->assertInstanceOf(\Illuminate\Http\JsonResponse::class, $response);
        $this->assertEquals(200, $response->getStatusCode());
        $this->assertJsonStringEqualsJsonString(
            json_encode(['data' => [['id' => 1, 'name' => 'Lot 1', 'city' => 'Paris', 'available_spaces' => 3]]]),
            $response->getContent()
        );
    }

    public function tearDown(): void
    {
        Mockery::close();
        parent::tearDown();
    }
}