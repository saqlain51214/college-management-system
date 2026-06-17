<?php

namespace App\Repositories\Interfaces;

use Illuminate\Database\Eloquent\Model;

interface RepositoryInterface
{
    public function all(array $columns = ['*'], array $relations = []);

    public function find(int $id, array $columns = ['*'], array $relations = []);

    public function findBySlug(string $slug);

    public function create(array $data): Model;

    public function update(int $id, array $data): bool;

    public function delete(int $id): bool;

    public function paginate(int $perPage = 15, array $columns = ['*']);
}
