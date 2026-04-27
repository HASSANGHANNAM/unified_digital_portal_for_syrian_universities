<?php

namespace App\Repositories\Contracts;

use App\Models\RequestTypeAvailability;
use Illuminate\Database\Eloquent\Collection;

interface RequestTypeAvailabilityRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): RequestTypeAvailability;
    public function update(RequestTypeAvailability $requestTypeAvailability, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?RequestTypeAvailability;
}