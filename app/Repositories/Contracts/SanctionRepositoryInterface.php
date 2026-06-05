<?php

namespace App\Repositories\Contracts;

use App\Models\Sanction;
use Illuminate\Database\Eloquent\Collection;

interface SanctionRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Sanction;
    public function update(Sanction $sanction, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Sanction;
    public function getStudentSanctions(int $studentId);
    public function getStudentSanctionById(int $studentId, int $sanctionId);
    public function updateResponse(int $sanctionId, array $data): bool;

}
