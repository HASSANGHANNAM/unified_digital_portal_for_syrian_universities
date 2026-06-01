<?php

namespace App\Repositories\Contracts;

use App\Models\RequestType;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface RequestTypeRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): RequestType;
    public function update(RequestType $requestType, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?RequestType;
    public function getByCollegeId(int $collegeId, array $request): Collection|LengthAwarePaginator;
    public function getByIdWithMedia(string $id): ?RequestType;
    public function getRequiredMediaTypes(string $requestTypeId): array;
}
