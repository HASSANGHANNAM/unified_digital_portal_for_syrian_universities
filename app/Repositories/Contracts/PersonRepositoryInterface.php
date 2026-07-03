<?php

namespace App\Repositories\Contracts;

use App\Models\Person;
use Illuminate\Database\Eloquent\Collection;

interface PersonRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): Person;
    public function update(Person $person, array $data): Person;
    public function delete(string $id): bool;
    public function findById(string $id): ?Person;
}
