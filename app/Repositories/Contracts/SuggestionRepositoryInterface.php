<?php

namespace App\Repositories\Contracts;

use App\Models\Suggestion;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface SuggestionRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Suggestion;
    public function update(Suggestion $suggestion, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Suggestion;
    public function getByCollegeId(string $collegeId, array $filters = [], int $perPage = 15, int $page = 1): LengthAwarePaginator;
    public function getFiltered(array $filters, int $studentId, int $perPage = 15, int $page = 1): LengthAwarePaginator;
    public function updateStatus(string $id, string $status): ?Suggestion;
}
