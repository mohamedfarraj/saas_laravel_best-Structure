<?php

namespace App\Modules\Base\Repositories;

use App\Modules\Base\Models\BaseModel;
use Illuminate\Database\Eloquent\ModelNotFoundException;

abstract class BaseRepository
{
    protected $model;

    public function __construct(BaseModel $model)
    {
        $this->model = $model;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        $record = $this->model->find($id);
        if (!$record) {
            throw new ModelNotFoundException("Record not found with id: {$id}");
        }
        return $record;
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
        $record = $this->find($id);
        return $record->delete();
    }
} 