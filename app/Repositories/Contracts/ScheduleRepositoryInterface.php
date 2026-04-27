<?php

namespace App\Repositories\Contracts;

use App\Models\Schedule;
use Illuminate\Database\Eloquent\Collection;

interface ScheduleRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Schedule;
    public function update(Schedule $schedule, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Schedule;
}