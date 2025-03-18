<?php

namespace App\Modules\Core\Users\Controllers;

use App\Modules\Base\Controllers\BaseController;
use App\Modules\Core\Users\Services\UsersService;
use App\Modules\Core\Users\Requests\StoreUsersRequest;
use App\Modules\Core\Users\Requests\UpdateUsersRequest;
use App\Modules\Core\Users\Resources\UsersResource;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;
use Illuminate\Http\Resources\Json\JsonResource;

class UsersController extends BaseController
{
    public function __construct(UsersService $service)
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
        $data = UsersResource::collection($this->service->all());
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
        $data = new UsersResource($this->service->find($id));
        return $this->respondWithSuccess($data);
    }

    /**
     * Create new record
     *
     * @param StoreUsersRequest $request
     * @return JsonResponse
     */
    public function store(StoreUsersRequest $request): JsonResponse
    {
        $data = new UsersResource($this->service->create($request->validated()));
        return $this->respondWithSuccess($data, 'Users created successfully');
    }

    /**
     * Update record
     *
     * @param UpdateUsersRequest $request
     * @param int $id
     * @return JsonResponse
     */
    public function update(UpdateUsersRequest $request, $id): JsonResponse
    {
        $data = new UsersResource($this->service->update($id, $request->validated()));
        return $this->respondWithSuccess($data, 'Users updated successfully');
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
        return $this->respondWithSuccess(null, 'Users deleted successfully');
    }
} 