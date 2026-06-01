<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Services\MediaService;
use Symfony\Component\HttpFoundation\StreamedResponse;

class FileStorageController extends Controller
{
    public function __construct(private MediaService $mediaService)
    {
        $this->middleware('auth:sanctum');
    }

    public function viewMedia(string $id): StreamedResponse
    {
        return $this->mediaService->viewMedia($id);
    }
}
