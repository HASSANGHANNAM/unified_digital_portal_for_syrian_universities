<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Validation\ValidationException;
use Laravel\Sanctum\PersonalAccessToken;


class TokenServices
{
    public function createAuthTokens(User $user): array
    {
        $accessToken = $user->createToken('access_token', ['*'], now()->addHour());
        $refreshToken = $user->createToken('refresh_token', ['refresh'], now()->addDays(30));
        $tokens['access_token'] = $accessToken->plainTextToken;
        $tokens['refresh_token'] = $refreshToken->plainTextToken;
        // $tokens['token_type'] =  'Bearer';
        $tokens['expires_in'] = 3600;
        $roles = $user->roles->map(function ($role) {
            return [
                'name' => $role->name
            ];
        });
        $data['tokens'] = $tokens;
        $data['roles'] = $roles;
        return $data;
    }

    public function refreshTokens(string $refreshToken): array
    {
        $token = PersonalAccessToken::findToken($refreshToken);

        if (!$token || !$token->can('refresh')) {
            throw ValidationException::withMessages([
                'token' => ['Invalid refresh token']
            ]);
        }

        $user = $token->tokenable;
        $token->delete();
        $user->tokens()->where('name', 'access_token')->delete();
        $data = $this->createAuthTokens($user);
        $data['user'] = $user;

        return $data;
    }

    public function revokeAllTokens(User $user): void
    {
        $user->tokens()->delete();
    }
}
