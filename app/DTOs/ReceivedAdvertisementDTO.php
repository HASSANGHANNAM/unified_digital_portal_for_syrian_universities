<?php

namespace App\DTOs;

use App\Models\Advertisement;

class ReceivedAdvertisementDTO
{
    public static function fromModel(Advertisement $advertisement): array
    {
        return [
            'id'         => $advertisement->id,
            'title'      => $advertisement->title,
            'message'    => $advertisement->message,
            'type'       => $advertisement->type,
            'created_at' => $advertisement->created_at->toDateTimeString(),
            'sender'     => [
                'id'   => $advertisement->user?->id,
                'name' => $advertisement->user?->person?->full_name ?? 'غير معروف',
            ],

        ];
    }
}
