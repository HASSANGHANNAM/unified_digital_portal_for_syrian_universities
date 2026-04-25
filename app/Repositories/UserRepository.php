<?php

namespace App\Repositories;

use App\Models\User;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Contracts\UserRepositoryInterface;
use Ramsey\Collection\Collection;

class UserRepository implements UserRepositoryInterface

{
    public function __construct(private User $user) {}

    public function create(array $data): User
    {
        return $this->user->create([
            'FirstnameAr' => $data['FirstnameAr'],
            'FirstnameEn' => $data['FirstnameEn'] ?? null,
            'MiddlenameAr' => $data['MiddlenameAr'] ?? null,
            'MiddlenameEn' => $data['MiddlenameEn'] ?? null,
            'LastnameAr' => $data['LastnameAr'],
            'LastnameEn' => $data['LastnameEn'] ?? null,
            'BirthPlaceAr' => $data['BirthPlaceAr'],
            'BirthPlaceEn' => $data['BirthPlaceEn'] ?? null,
            'BirthDate' => $data['BirthDate'],
            'NationalNumber' => $data['NationalNumber'],
            'IdFrontFace' => $data['IdFrontFace'] ?? null,
            'IdBackFace' => $data['IdBackFace'] ?? null,
            'CurrentLocationAr' => $data['CurrentLocationAr'],
            'CurrentLocationEn' => $data['CurrentLocationEn'] ?? null,
            'ContactNumber' => $data['ContactNumber'],
            'Email' => $data['Email'],
            'password' => Hash::make($data['password']),
            'email_verified_at' => $data['email_verified_at'] ?? null,
            'fcm_token'=>$data['fcm_token'] ?? null
        ]);
    }
    public function all(): Collection
    {
        throw new \Exception('Not implemented');
    }
    public function findByEmail(string $Email): ?User
    {
        return $this->user->where('Email', $Email)->first();
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

    public function getProfile(User $user): array
    {
        return [
            'id' => $user->id,
            'FirstnameAr' => $user->FirstnameAr,
            'FirstnameEn' => $user->FirstnameEn,
            'MiddlenameAr' => $user->MiddlenameAr,
            'MiddlenameEn' => $user->MiddlenameEn,
            'LastnameAr' => $user->LastnameAr,
            'LastnameEn' => $user->LastnameEn,
            'BirthPlaceAr' => $user->BirthPlaceAr,
            'BirthPlaceEn' => $user->BirthPlaceEn,
            'BirthDate' => $user->BirthDate,
            'NationalNumber' => $user->NationalNumber,
            'NationalNumber' => $user->NationalNumber,
            'CurrentLocationEn' => $user->CurrentLocationEn,
            'ContactNumber' => $user->ContactNumber,
            'Email' => $user->Email,
            'IdFrontFace' => $user->IdFrontFace ? url("/api/users/{$user->id}/id-front") : null,
            'IdBackFace' => $user->IdBackFace ? url("/api/users/{$user->id}/id-back") : null,
        ];
    }
}
