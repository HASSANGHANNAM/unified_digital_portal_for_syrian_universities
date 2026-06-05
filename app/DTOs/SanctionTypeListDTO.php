<?php

namespace App\DTOs;

use Illuminate\Pagination\LengthAwarePaginator;

class SanctionTypeListDTO
{
    public static function fromPaginator(LengthAwarePaginator $paginator): array
    {
        $data = array_map(function ($item) {
            return SanctionTypeDTO::fromModel($item)->toArray();
        }, $paginator->items());

        return [
            'data' => $data,
            'meta' => [
                'current_page' => $paginator->currentPage(),
                'per_page' => $paginator->perPage(),
                'total' => $paginator->total(),
                'last_page' => $paginator->lastPage(),
            ],
        ];
    }
}
