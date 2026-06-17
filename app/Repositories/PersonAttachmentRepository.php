<?php

namespace App\Repositories;

use App\Models\PersonAttachment;
use App\Repositories\Contracts\PersonAttachmentRepositoryInterface;
use Illuminate\Database\Eloquent\Collection;

class PersonAttachmentRepository implements PersonAttachmentRepositoryInterface
{
    public function __construct(private PersonAttachment $model) {}

    public function all(): Collection
    {
        return $this->model->all();
    }

    public function create(array $data): PersonAttachment
    {
        return $this->model->create($data);
    }

    public function update(PersonAttachment $personAttachment, array $data): bool
    {
        return $personAttachment->update($data);
    }

    public function delete(string $id): bool
    {
        $model = $this->findById($id);
        if (!$model) {
            return false;
        }
        return $model->delete();
    }

    public function findById(string $id): ?PersonAttachment
    {
        return $this->model->find($id);
    }

    public function getByPersonId(int $personId): Collection
    {
        return $this->model->where('person_id', $personId)->latest()->get();
    }
}
