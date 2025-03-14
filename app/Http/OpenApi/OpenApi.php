<?php
namespace App\Http\OpenApi;
use OpenApi\Annotations as OA;

/**
 * @OA\OpenApi(
 *     @OA\Info(
 *         version="1.0.0",
 *         title="Parking API",
 *         description="API for parking management system",
 *         @OA\Contact(
 *             email="support@example.com"
 *         )
 *     ),
 *     @OA\Server(
 *         url="http://api.example.com",
 *         description="Production server"
 *     )
 * )
 * @OA\SecurityScheme(
 *     securityScheme="sanctumAuth",
 *     type="http",
 *     scheme="bearer",
 *     bearerFormat="JWT",
 *     description="Enter your bearer token"
 * )
 * @OA\Schema(
 *     schema="User",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="John Doe"),
 *     @OA\Property(property="email", type="string", example="john@example.com")
 * )
 * @OA\Schema(
 *     schema="Parking",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="name", type="string", example="Downtown Parking"),
 *     @OA\Property(property="location", type="string", example="123 Main St"),
 *     @OA\Property(property="capacity", type="integer", example=100)
 * )
 * @OA\Schema(
 *     schema="Reservation",
 *     type="object",
 *     @OA\Property(property="id", type="integer", example=1),
 *     @OA\Property(property="user_id", type="integer", example=1),
 *     @OA\Property(property="parking_id", type="integer", example=1),
 *     @OA\Property(property="start_time", type="string", format="date-time"),
 *     @OA\Property(property="end_time", type="string", format="date-time")
 * )
 * @OA\Schema(
 *     schema="Error",
 *     type="object",
 *     @OA\Property(property="message", type="string", example="Error message"),
 *     @OA\Property(property="code", type="integer", example=400)
 * )
 */
class OpenApi
{
    /**
     * @OA\Post(
     *     path="/register",
     *     tags={"Authentication"},
     *     summary="Register new user",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="User registered",
     *         @OA\JsonContent(ref="#/components/schemas/User")
     *     ),
     *     @OA\Response(response=400, description="Validation error", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function register() {}

    /**
     * @OA\Post(
     *     path="/login",
     *     tags={"Authentication"},
     *     summary="User login",
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="email", type="string", example="john@example.com"),
     *             @OA\Property(property="password", type="string", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Login successful",
     *         @OA\JsonContent(
     *             @OA\Property(property="token", type="string", example="1|abc123...")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function login() {}

    /**
     * @OA\Post(
     *     path="/logout",
     *     tags={"Authentication"},
     *     summary="User logout",
     *     security={{"sanctumAuth": {}}},
     *     @OA\Response(response=200, description="Logout successful"),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function logout() {}

    /**
     * @OA\Get(
     *     path="/user",
     *     tags={"Authentication"},
     *     summary="Get authenticated user",
     *     security={{"sanctumAuth": {}}},
     *     @OA\Response(response=200, description="User data", @OA\JsonContent(ref="#/components/schemas/User")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function getUser() {}

    /**
     * @OA\Get(
     *     path="/parking/search",
     *     tags={"Parking"},
     *     summary="Search available parking",
     *     security={{"sanctumAuth": {}}},
     *     @OA\Parameter(name="location", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Parking list", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Parking"))),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function searchParking() {}

    /**
     * @OA\Post(
     *     path="/parking/reserve",
     *     tags={"Parking"},
     *     summary="Create parking reservation",
     *     security={{"sanctumAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="parking_id", type="integer", example=1),
     *             @OA\Property(property="start_time", type="string", format="date-time"),
     *             @OA\Property(property="end_time", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Reservation created", @OA\JsonContent(ref="#/components/schemas/Reservation")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function reserveParking() {}

    /**
     * @OA\Put(
     *     path="/reservations/{id}",
     *     tags={"Parking"},
     *     summary="Update reservation",
     *     security={{"sanctumAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="start_time", type="string", format="date-time"),
     *             @OA\Property(property="end_time", type="string", format="date-time")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Reservation updated", @OA\JsonContent(ref="#/components/schemas/Reservation")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function updateReservation() {}

    /**
     * @OA\Delete(
     *     path="/reservations/{id}",
     *     tags={"Parking"},
     *     summary="Delete reservation",
     *     security={{"sanctumAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Reservation deleted"),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function deleteReservation() {}

    /**
     * @OA\Get(
     *     path="/reservations",
     *     tags={"Parking"},
     *     summary="Get user reservations",
     *     security={{"sanctumAuth": {}}},
     *     @OA\Response(response=200, description="Reservations list", @OA\JsonContent(type="array", @OA\Items(ref="#/components/schemas/Reservation"))),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function getReservations() {}

    /**
     * @OA\Post(
     *     path="/parkings",
     *     tags={"Admin"},
     *     summary="Create parking lot",
     *     security={{"sanctumAuth": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Parking")
     *     ),
     *     @OA\Response(response=201, description="Parking created", @OA\JsonContent(ref="#/components/schemas/Parking")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function createParking() {}

    /**
     * @OA\Put(
     *     path="/parkings/{id}",
     *     tags={"Admin"},
     *     summary="Update parking lot",
     *     security={{"sanctumAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/Parking")
     *     ),
     *     @OA\Response(response=200, description="Parking updated", @OA\JsonContent(ref="#/components/schemas/Parking")),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function updateParking() {}

    /**
     * @OA\Delete(
     *     path="/parkings/{id}",
     *     tags={"Admin"},
     *     summary="Delete parking lot",
     *     security={{"sanctumAuth": {}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=204, description="Parking deleted"),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function deleteParking() {}

    /**
     * @OA\Get(
     *     path="/parking/stats",
     *     tags={"Admin"},
     *     summary="Get parking statistics",
     *     security={{"sanctumAuth": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Parking statistics",
     *         @OA\JsonContent(
     *             @OA\Property(property="total", type="integer", example=100),
     *             @OA\Property(property="occupied", type="integer", example=75)
     *         )
     *     ),
     *     @OA\Response(response=401, description="Unauthorized", @OA\JsonContent(ref="#/components/schemas/Error")),
     *     @OA\Response(response=403, description="Forbidden", @OA\JsonContent(ref="#/components/schemas/Error"))
     * )
     */
    public function getParkingStats() {}
}