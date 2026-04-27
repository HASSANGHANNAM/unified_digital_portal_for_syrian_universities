<?php

namespace App\Repositories;

use App\Models\RequestTypeAvailability;
use App\Repositories\Contracts\RequestTypeAvailabilityRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class RequestTypeAvailabilityRepository implements RequestTypeAvailabilityRepositoryInterface
{
    public function __construct(private RequestTypeAvailability $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): RequestTypeAvailability
    {
        return $this->model->create($data);
    }

    public function update(RequestTypeAvailability $requestTypeAvailability, array $data): bool
    {
        return $requestTypeAvailability->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?RequestTypeAvailability
    {
        return $this->model->find($id);
    }
}