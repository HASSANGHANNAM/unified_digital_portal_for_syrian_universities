<?php

namespace App\Repositories\Contracts;

use App\Models\University;
use Illuminate\Database\Eloquent\Collection;

interface UniversityRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): University;
    public function update(University $university, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?University;
    public function getUniversitiesWithColleges(array $filters): Collection;
}
