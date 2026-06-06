<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;
use Ramsey\Collection\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function __construct(private User $user) {}

    public function create(array $data): User
    {
        return $this->user->create([
            'username' => $data['username'],
            'email' => $data['email'] ?? null,
            'last_login' => $data['last_login'] ?? null,
            'new_password' => $data['new_password'] ?? null,
            'status' => $data['status'] ?? 'active',
            'password' => Hash::make($data['password']),
            'email_verified_at' => $data['email_verified_at'] ?? null,
            'person_id' => $data['person_id'] ?? null,
        ]);
    }

    public function all(): Collection
    {
        throw new \Exception('Not implemented');
    }

    public function findByEmail(string $email): ?User
    {
        return $this->user->where('email', $email)->first();
    }

    public function findByUserName(string $username): ?User
    {
        return $this->user->where('username', $username)->first();
    }

    public function findById(int $id): ?User
    {
        return $this->user->find($id);
    }

    public function assignRole(User $user, string $roleName): void
    {
        $user->assignRole($roleName);
    }

    public function update(User $user, array $data): bool
    {
        return $user->update($data);
    }

    public function getUsersWithFilters(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        return $this->user
            ->with(['roles', 'person'])
            ->when(isset($filters['role']), function ($query) use ($filters) {
                $query->whereHas('roles', function ($roleQuery) use ($filters) {
                    $roleQuery->where('name', $filters['role']);
                });
            })
            ->when(isset($filters['status']), function ($query) use ($filters) {
                $query->where('status', $filters['status']);
            })
            ->when(isset($filters['search']), function ($query) use ($filters) {
                $query->where(function ($inner) use ($filters) {
                    $inner->where('username', 'like', '%' . $filters['search'] . '%')
                        ->orWhere('email', 'like', '%' . $filters['search'] . '%')
                        ->orWhereHas('person', function ($personQuery) use ($filters) {
                            $personQuery->where('full_name', 'like', '%' . $filters['search'] . '%');
                        });
                });
            })
            ->orderBy('id', 'desc')
            ->paginate($perPage);
    }

    public function getProfile(User $user): array
    {
        return [
            'id' => $user->id,
            'username' => $user->username,
            'email' => $user->email,
            'status' => $user->status,
            'last_login' => $user->last_login,
            'created_at' => $user->created_at,
        ];
    }
}
