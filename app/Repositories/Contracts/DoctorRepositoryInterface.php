<?php

namespace App\Repositories\Contracts;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface DoctorRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Doctor;
    public function update(Doctor $doctor, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Doctor;
    public function getUniversitiesByPersonId(int $personId): array;
    public function getCoursesByPersonIdAndCollege(
        int $personId,
        int $collegeId,
        array $filters = [],
        int $perPage = 15,
        ?int $page = null
    ): LengthAwarePaginator;
    public function getDoctors(array $filters, int $perPage = 15): LengthAwarePaginator;
}
