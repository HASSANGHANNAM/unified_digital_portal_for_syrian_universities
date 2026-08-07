<?php

namespace App\Repositories;

use App\Models\Suggestion;
use App\Repositories\Contracts\SuggestionRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Ramsey\Uuid\Type\Integer;

class SuggestionRepository implements SuggestionRepositoryInterface
{
    public function __construct(private Suggestion $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): Suggestion
    {
        return $this->model->create($data);
    }

    public function update(Suggestion $suggestion, array $data): bool
    {
        return $suggestion->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?Suggestion
    {
        return $this->model->find($id);
    }
    public function getFiltered(array $filters, int $studentId, int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        $query = $this->model->newQuery()->where('student_id', $studentId);

        if (!empty($filters['content'])) {
            $query->where('content', 'like', '%' . $filters['content'] . '%');
        }

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
    public function getByCollegeId(string $collegeId, array $filters = [], int $perPage = 15, int $page = 1): LengthAwarePaginator
    {
        $query = $this->model->newQuery()
            ->with(['student.person'])
            ->whereHas('student', function ($query) use ($collegeId) {
                $query->where('college_id', $collegeId);
            });

        if (!empty($filters['content'])) {
            $query->where('content', 'like', '%' . $filters['content'] . '%');
        }
        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        $query->orderBy('created_at', 'desc');

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
    public function updateStatus(string $id, string $status): ?Suggestion
    {
        $suggestion = $this->findById($id);
        if (!$suggestion) {
            return null;
        }
        if ($suggestion->status !== 'قيد المراجعة') {
            return null;
        }
        $suggestion->status = $status;
        $suggestion->save();
        return $suggestion;
    }
}
