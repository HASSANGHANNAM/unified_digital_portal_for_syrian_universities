<?php

namespace App\DTOs;

use App\Models\Advertisement;

class AdvertisementDetailDTO
{
    public static function fromModel(Advertisement $advertisement): array
    {
        $attachments = $advertisement->attachments->map(function ($attachment) {
            return [
                'id'   => $attachment->id,
                'name' => $attachment->name,
                'type' => $attachment->type,
                'advertisement_attachment_url' => '/api/V1/advertisement_attachments/' . $attachment->id,
            ];
        })->toArray();

        return [
            'id'         => $advertisement->id,
            'title'      => $advertisement->title,
            'message'    => $advertisement->message,
            'created_at' => $advertisement->created_at->toDateTimeString(),
            'sender'     => [
                'id'   => $advertisement->user?->id,
                'name' => $advertisement->user?->person?->full_name ?? 'غير معروف',
            ],
            'attachments' => $attachments,
        ];
    }
}
