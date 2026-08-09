<?php

namespace App\Repositories;

use App\Models\Advertisement;
use App\Repositories\Contracts\AdvertisementRepositoryInterface;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class AdvertisementRepository implements AdvertisementRepositoryInterface
{
    public function getMySendAdvertisements(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $query = Advertisement::with(['user'])
            ->where('user_id', $userId)
            ->orderBy('created_at', 'desc');
        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? $perPage;

        return $query->paginate($perPage, ['*'], 'page', $page);
    }
    public function getMyReceivedAdvertisements(int $studentId, array $filters = [], int $perPage = 15): LengthAwarePaginator
    {
        $page = $filters['page'] ?? 1;
        $perPage = $filters['per_page'] ?? $perPage;

        return Advertisement::whereHas('students', function ($query) use ($studentId) {
            $query->where('student_id', $studentId);
        })
            ->with(['user.person'])
            ->orderBy('created_at', 'desc')
            ->paginate($perPage, ['*'], 'page', $page);
    }
    public function find(int $advertisementId): ?Advertisement
    {
        return Advertisement::where('id', $advertisementId)
            ->with(['user.person', 'attachments'])
            ->first();
    }
}
