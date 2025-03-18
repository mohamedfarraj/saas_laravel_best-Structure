<?php

namespace App\Modules\Tenant\Users\Services;

use App\Modules\Base\Services\BaseService;
use App\Modules\Tenant\Users\Repositories\UsersRepository;

class UsersService extends BaseService
{
    public function __construct(UsersRepository $usersRepository)
    {
        parent::__construct($usersRepository);
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