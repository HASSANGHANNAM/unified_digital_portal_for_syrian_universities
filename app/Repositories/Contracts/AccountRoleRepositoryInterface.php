<?php

namespace App\Repositories\Contracts;

use App\Models\AccountRole;
use Illuminate\Database\Eloquent\Collection;

interface AccountRoleRepositoryInterface
{
    public function all(): Collection;
    public function create(array $data): AccountRole;
    public function update(AccountRole $accountRole, array $data): bool;
    public function delete(string $id): bool;
    public function findById(string $id): ?AccountRole;
}