<?php

namespace App\Repositories;

use App\Models\RequestMedia;
use App\Repositories\Contracts\RequestMediaRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RequestMediaRepository implements RequestMediaRepositoryInterface
{
    public function __construct(private RequestMedia $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): RequestMedia
    {
        return $this->model->create($data);
    }

    public function update(RequestMedia $requestMedia, array $data): bool
    {
        return $requestMedia->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?RequestMedia
    {
        return $this->model->find($id);
    }
}