<?php

namespace App\Repositories\Contracts;

use App\Models\Advertisement;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

interface AdvertisementRepositoryInterface
{
    public function getMySendAdvertisements(int $userId, array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function getMyReceivedAdvertisements(int $studentId, array $filters = [], int $perPage = 15): LengthAwarePaginator;
    public function find(int $advertisementId): ?Advertisement;
}
