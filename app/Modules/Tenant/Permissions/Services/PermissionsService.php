<?php

namespace App\Modules\Tenant\Permissions\Services;

use App\Modules\Base\Services\BaseService;
use App\Modules\Tenant\Permissions\Repositories\PermissionsRepository;

class PermissionsService extends BaseService
{
    public function __construct(PermissionsRepository $permissionsRepository)
    {
        parent::__construct($permissionsRepository);
    }

    public function all()
    {
        return $this->repository->all();
    }

    public function find($id)
    {
        return $this->repository->find($id);
    }

    public function create(array $data)
    {
        return $this->repository->create($data);
    }

    public function update($id, array $data)
    {
        return $this->repository->update($id, $data);
    }

    public function delete($id)
    {
        return $this->repository->delete($id);
    }
} 