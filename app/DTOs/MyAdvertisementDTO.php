<?php

namespace App\DTOs;

use App\Models\Advertisement;

class MyAdvertisementDTO
{
    /**
     * تحويل نموذج الإعلان إلى مصفوفة استجابة
     */
    public static function fromModel(Advertisement $advertisement): array
    {
        return [
            'id'         => $advertisement->id,
            'title'      => $advertisement->title,
            'message'    => $advertisement->message,
            'type'       => $advertisement->type,
            'created_at' => $advertisement->created_at->toDateTimeString(),
        ];
    }
}
