<?php

namespace App\Services;

use App\Repositories\BaseRepository;
use Illuminate\Database\Eloquent\Model;

abstract class BaseService
{
    public function __construct(protected BaseRepository $repository) {}

    public function all(array $columns = ['*'], array $relations = [])
    {
        return $this->repository->all($columns, $relations);
    }

    public function find(int $id, array $columns = ['*'], array $relations = [])
    {
        return $this->repository->find($id, $columns, $relations);
    }

    public function create(array $data): Model
    {
        return $this->repository->create($data);
    }

    public function update(int $id, array $data): bool
    {
        return $this->repository->update($id, $data);
    }

    public function delete(int $id): bool
    {
        return $this->repository->delete($id);
    }

    public function paginate(int $perPage = 15)
    {
        return $this->repository->paginate($perPage);
    }
}
