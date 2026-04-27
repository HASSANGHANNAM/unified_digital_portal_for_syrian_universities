<?php

namespace App\Repositories\Contracts;

use App\Models\PersonAttachment;
use Illuminate\Database\Eloquent\Collection;

interface PersonAttachmentRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): PersonAttachment;
    public function update(PersonAttachment $personAttachment, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?PersonAttachment;
}