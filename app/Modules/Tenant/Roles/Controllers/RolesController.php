<?php

namespace App\Modules\Tenant\Roles\Controllers;

use App\Modules\Base\Controllers\BaseController;
use App\Modules\Tenant\Roles\Services\RolesService;
use App\Modules\Tenant\Roles\Requests\StoreRolesRequest;
use App\Modules\Tenant\Roles\Requests\UpdateRolesRequest;
use App\Modules\Tenant\Roles\Resources\RolesResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class RolesController extends BaseController
{
    public function __construct(RolesService $service)
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
        $data = RolesResource::collection($this->service->all());
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
        $data = new RolesResource($this->service->find($id));
        return $this->respondWithSuccess($data);
    }

    /**
     * Create new record
     *
     * @param StoreRolesRequest $request
     * @return JsonResponse
     */
    public function store(StoreRolesRequest $request): JsonResponse
    {
        $data = new RolesResource($this->service->create($request->validated()));
        return $this->respondWithSuccess($data, 'Roles created successfully');
    }

    /**
     * Update record
     *
     * @param UpdateRolesRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateRolesRequest $request, $id): JsonResponse
    {
        $data = new RolesResource($this->service->update($id, $request->validated()));
        return $this->respondWithSuccess($data, 'Roles updated successfully');
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
        return $this->respondWithSuccess(null, 'Roles deleted successfully');
    }
} 