<?php

namespace App\Modules\Tenant\Roles\Repositories;

use App\Modules\Base\Repositories\BaseRepository;
use App\Modules\Tenant\Roles\Models\Roles;

class RolesRepository extends BaseRepository
{
    public function __construct(Roles $model)
    {
        parent::__construct($model);
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $record = $this->find($id);
        $record->update($data);
        return $record;
    }

    public function delete($id)
    {
        return $this->find($id)->delete();
    }
} 