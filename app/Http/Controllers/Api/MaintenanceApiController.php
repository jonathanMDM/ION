<?php

namespace App\Http\Controllers\Api;

use App\Models\Maintenance;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class MaintenanceApiController extends ApiController
{
    /**
     * @OA\Get(
     *     path="/api/v2/maintenances",
     *     tags={"Maintenances"},
     *     summary="Get list of maintenances",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="asset_id", in="query", @OA\Schema(type="integer")),
     *     @OA\Parameter(name="status", in="query", @OA\Schema(type="string")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function index(Request $request)
    {
        $perPage = $request->get('per_page', 15);
        $query = Maintenance::with(['asset', 'user']);

        if ($request->has('asset_id')) {
            $query->where('asset_id', $request->asset_id);
        }

        if ($request->has('status')) {
            $query->where('status', $request->status);
        }

        $maintenances = $query->latest()->paginate($perPage);
        
        return $this->paginatedResponse($maintenances, 'Maintenances retrieved successfully');
    }

    /**
     * @OA\Post(
     *     path="/api/v2/maintenances",
     *     tags={"Maintenances"},
     *     summary="Create a new maintenance",
     *     security={{"sanctum":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"asset_id","type","scheduled_date"},
     *             @OA\Property(property="asset_id", type="integer"),
     *             @OA\Property(property="type", type="string", enum={"preventive", "corrective", "inspection"}),
     *             @OA\Property(property="scheduled_date", type="string", format="date"),
     *             @OA\Property(property="description", type="string"),
     *             @OA\Property(property="cost", type="number")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Maintenance created successfully")
     * )
     */
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'asset_id' => 'required|exists:assets,id',
            'type' => 'required|in:preventive,corrective,inspection',
            'scheduled_date' => 'required|date',
            'description' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $data = $validator->validated();
        $data['user_id'] = $request->user()->id;
        $data['status'] = $data['status'] ?? 'pending';

        $maintenance = Maintenance::create($data);

        return $this->successResponse(
            $maintenance->load(['asset', 'user']),
            'Maintenance created successfully',
            201
        );
    }

    /**
     * @OA\Get(
     *     path="/api/v2/maintenances/{id}",
     *     tags={"Maintenances"},
     *     summary="Get maintenance by ID",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Successful operation")
     * )
     */
    public function show($id)
    {
        $maintenance = Maintenance::with(['asset', 'user'])->find($id);

        if (!$maintenance) {
            return $this->notFoundResponse('Maintenance not found');
        }

        return $this->successResponse($maintenance, 'Maintenance retrieved successfully');
    }

    /**
     * @OA\Put(
     *     path="/api/v2/maintenances/{id}",
     *     tags={"Maintenances"},
     *     summary="Update maintenance",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Maintenance updated successfully")
     * )
     */
    public function update(Request $request, $id)
    {
        $maintenance = Maintenance::find($id);

        if (!$maintenance) {
            return $this->notFoundResponse('Maintenance not found');
        }

        $validator = Validator::make($request->all(), [
            'type' => 'sometimes|required|in:preventive,corrective,inspection',
            'scheduled_date' => 'sometimes|required|date',
            'completed_date' => 'nullable|date',
            'description' => 'nullable|string',
            'notes' => 'nullable|string',
            'cost' => 'nullable|numeric|min:0',
            'status' => 'nullable|in:pending,in_progress,completed,cancelled',
        ]);

        if ($validator->fails()) {
            return $this->validationErrorResponse($validator->errors());
        }

        $maintenance->update($validator->validated());

        return $this->successResponse(
            $maintenance->load(['asset', 'user']),
            'Maintenance updated successfully'
        );
    }

    /**
     * @OA\Delete(
     *     path="/api/v2/maintenances/{id}",
     *     tags={"Maintenances"},
     *     summary="Delete maintenance",
     *     security={{"sanctum":{}}},
     *     @OA\Parameter(name="id", in="path", required=true, @OA\Schema(type="integer")),
     *     @OA\Response(response=200, description="Maintenance deleted successfully")
     * )
     */
    public function destroy($id)
    {
        $maintenance = Maintenance::find($id);

        if (!$maintenance) {
            return $this->notFoundResponse('Maintenance not found');
        }

        $maintenance->delete();

        return $this->successResponse(null, 'Maintenance deleted successfully');
    }
}
