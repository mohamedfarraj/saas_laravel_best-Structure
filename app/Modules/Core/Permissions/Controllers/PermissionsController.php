<?php

namespace App\Modules\Core\Permissions\Controllers;

use App\Modules\Base\Controllers\BaseController;
use App\Modules\Core\Permissions\Services\PermissionsService;
use App\Modules\Core\Permissions\Requests\StorePermissionsRequest;
use App\Modules\Core\Permissions\Requests\UpdatePermissionsRequest;
use App\Modules\Core\Permissions\Resources\PermissionsResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class PermissionsController extends BaseController
{
    public function __construct(PermissionsService $service)
    {
        $this->service = $service;
    }

    /**
     * Get all records
     *
     * @return JsonResponse
     */
    public function index(): JsonResponse
    {
        $data = PermissionsResource::collection($this->service->all());
        return $this->respondWithSuccess($data);
    }

    /**
     * Get single record
     *
     * @param int $id
     * @return JsonResponse
     */
    public function show($id): JsonResponse
    {
        $data = new PermissionsResource($this->service->find($id));
        return $this->respondWithSuccess($data);
    }

    /**
     * Create new record
     *
     * @param StorePermissionsRequest $request
     * @return JsonResponse
     */
    public function store(StorePermissionsRequest $request): JsonResponse
    {
        $data = new PermissionsResource($this->service->create($request->validated()));
        return $this->respondWithSuccess($data, 'Permissions created successfully');
    }

    /**
     * Update record
     *
     * @param UpdatePermissionsRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdatePermissionsRequest $request, $id): JsonResponse
    {
        $data = new PermissionsResource($this->service->update($id, $request->validated()));
        return $this->respondWithSuccess($data, 'Permissions updated successfully');
    }

    /**
     * Delete record
     *
     * @param int $id
     * @return JsonResponse
     */
    public function destroy($id): JsonResponse
    {
        $this->service->delete($id);
        return $this->respondWithSuccess(null, 'Permissions deleted successfully');
    }
} 