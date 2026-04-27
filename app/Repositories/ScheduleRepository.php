<?php

namespace App\Repositories;

use App\Models\Schedule;
use App\Repositories\Contracts\ScheduleRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class ScheduleRepository implements ScheduleRepositoryInterface
{
    public function __construct(private Schedule $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Schedule
    {
        return $this->model->create($data);
    }

    public function update(Schedule $schedule, array $data): bool
    {
        return $schedule->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Schedule
    {
        return $this->model->find($id);
    }
}