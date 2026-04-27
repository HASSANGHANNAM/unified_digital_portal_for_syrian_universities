<?php

namespace App\Repositories;

use App\Models\RequestTypeMedia;
use App\Repositories\Contracts\RequestTypeMediaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RequestTypeMediaRepository implements RequestTypeMediaRepositoryInterface
{
    public function __construct(private RequestTypeMedia $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): RequestTypeMedia
    {
        return $this->model->create($data);
    }

    public function update(RequestTypeMedia $requestTypeMedia, array $data): bool
    {
        return $requestTypeMedia->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?RequestTypeMedia
    {
        return $this->model->find($id);
    }
}