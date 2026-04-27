<?php

namespace App\Repositories\Contracts;

use App\Models\StudentRequest;
use Illuminate\Database\Eloquent\Collection;

interface StudentRequestRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): StudentRequest;
    public function update(StudentRequest $studentRequest, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?StudentRequest;
}