<?php

namespace App\Repositories\Contracts;

use App\Models\Suggestion;
use Illuminate\Database\Eloquent\Collection;

interface SuggestionRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Suggestion;
    public function update(Suggestion $suggestion, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?Suggestion;
}