<?php

namespace App\Modules\Core\Roles\Services;

use App\Modules\Base\Services\BaseService;
use App\Modules\Core\Roles\Repositories\RolesRepository;

class RolesService extends BaseService
{
    public function __construct(RolesRepository $rolesRepository)
    {
        parent::__construct($rolesRepository);
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