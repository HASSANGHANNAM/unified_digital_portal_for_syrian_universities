<?php

namespace App\Repositories;

use App\Models\RequestType;
use App\Repositories\Contracts\RequestTypeRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

class RequestTypeRepository implements RequestTypeRepositoryInterface
{
    public function __construct(private RequestType $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): RequestType
    {
        return $this->model->create($data);
    }

    public function update(RequestType $requestType, array $data): bool
    {
        return $requestType->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?RequestType
    {
        return $this->model->find($id);
    }

    public function getByIdWithMedia(string $id): ?RequestType
    {
        return $this->model->with('requestTypeMedia')->find($id);
    }

    public function getRequiredMediaTypes(string $requestTypeId): array
    {
        $type = $this->model->with('requestTypeMedia')->find($requestTypeId);
        if (!$type) {
            return [];
        }
        return $type->requestTypeMedia->pluck('type')->toArray();
    }

    public function getByCollegeId(int $collegeId, array $request): Collection|LengthAwarePaginator
    {
        $query = $this->model->query();
        $name = $request['name'] ?? null;
        $isAvailable = isset($request['is_available']) ? filter_var($request['is_available'], FILTER_VALIDATE_BOOLEAN, FILTER_NULL_ON_FAILURE) : null;
        $perPage = isset($request['per_page']) ? max(1, min(100, (int) $request['per_page'])) : 50;

        $query->whereHas('availability', function ($q) use ($collegeId, $isAvailable) {
            $q->where('college_id', $collegeId);
            if ($isAvailable !== null) {
                $q->where('is_available', $isAvailable);
            }
        })->with(['availability' => function ($q) use ($collegeId, $isAvailable) {
            $q->where('college_id', $collegeId);
            if ($isAvailable !== null) {
                $q->where('is_available', $isAvailable);
            }
        }]);


        if ($name !== null && $name !== '') {
            $query->where('name', 'like', '%' . $name . '%');
        }

        return $query->paginate($perPage);
    }
}
