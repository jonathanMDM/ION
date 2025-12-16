<?php

namespace App\Http\Controllers\Api;

use App\Models\Location;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class LocationApiController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v2/locations",
     *     tags={"Locations"},
     *     summary="Get list of locations",
     *     security={{"sanctum":{}}},
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 50);
        $locations = Location::withCount('assets')->paginate($perPage);
        
        return $this->paginatedResponse($locations, 'Locations retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/v2/locations",
     *     tags={"Locations"},
     *     summary="Create a new location",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name"},
     *             @OA\Property(property="name", type="string"),
     *             @OA\Property(property="address", type="string")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Location created successfully")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $location = Location::create($validator->validated());

        return $this->successResponse($location, 'Location created successfully', 201);
    }

    /**
     * @OA\Get(
     *     path="/api/v2/locations/{id}",
     *     tags={"Locations"},
     *     summary="Get location by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function show($id)
    {
        $location = Location::withCount('assets')->with('assets')->find($id);

        if (!$location) {
            return $this->notFoundResponse('Location not found');
        }

        return $this->successResponse($location, 'Location retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/v2/locations/{id}",
     *     tags={"Locations"},
     *     summary="Update location",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Location updated successfully")
     * )
     */
    public function update(Request $request, $id)
    {
        $location = Location::find($id);

        if (!$location) {
            return $this->notFoundResponse('Location not found');
        }

        $validator = Validator::make($request->all(), [
            'name' => 'sometimes|required|string|max:255',
            'address' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $location->update($validator->validated());

        return $this->successResponse($location, 'Location updated successfully');
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/locations/{id}",
     *     tags={"Locations"},
     *     summary="Delete location",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Location deleted successfully")
     * )
     */
    public function destroy($id)
    {
        $location = Location::find($id);

        if (!$location) {
            return $this->notFoundResponse('Location not found');
        }

        if ($location->assets()->count() > 0) {
            return $this->errorResponse('Cannot delete location with associated assets', 400);
        }

        $location->delete();

        return $this->successResponse(null, 'Location deleted successfully');
    }
}
