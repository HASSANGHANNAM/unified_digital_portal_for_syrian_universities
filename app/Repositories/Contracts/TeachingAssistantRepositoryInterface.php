<?php

namespace App\Repositories\Contracts;

use App\Models\TeachingAssistant;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

interface TeachingAssistantRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): TeachingAssistant;
    public function update(TeachingAssistant $teachingAssistant, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?TeachingAssistant;
    public function getUniversitiesByPersonId(int $personId): array;
    public function getCoursesByPersonIdAndCollege(
        int $personId,
        int $collegeId,
        array $filters = [],
        int $perPage = 15,
        ?int $page = null
    ): LengthAwarePaginator;
}
