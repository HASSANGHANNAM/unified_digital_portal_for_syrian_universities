<?php

namespace App\Repositories\Contracts;

use App\Models\TeachingAssistant;
use Illuminate\Database\Eloquent\Collection;

interface TeachingAssistantRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): TeachingAssistant;
    public function update(TeachingAssistant $teachingAssistant, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?TeachingAssistant;
}