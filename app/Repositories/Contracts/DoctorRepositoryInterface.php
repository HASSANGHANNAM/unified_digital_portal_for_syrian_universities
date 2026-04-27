<?php

namespace App\Repositories\Contracts;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Collection;

interface DoctorRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Doctor;
    public function update(Doctor $doctor, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Doctor;
}