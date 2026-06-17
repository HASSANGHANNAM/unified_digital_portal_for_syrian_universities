<?php

namespace App\Repositories\Contracts;

use App\Models\StudentVerification;
use Illuminate\Database\Eloquent\Collection;

interface StudentVerificationRepositoryInterface
{
    public function all(): Collection;

    public function create(array $data): StudentVerification;

    public function findById(string $id): ?StudentVerification;

    public function query();
}
