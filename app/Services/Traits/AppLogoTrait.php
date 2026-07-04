<?php

namespace App\Services\Traits;

use Illuminate\Support\Facades\Storage;

trait AppLogoTrait
{
    protected function getAppLogoPath(): string
    {
        return 'private/logos/myapp/8a7b3f2e-1c9d-4e5b-8f2a-9d3c7b1e5f4d.png';
    }

    public function getAppLogoBase64(): ?string
    {
        $path = $this->getAppLogoPath();
        if (!Storage::disk('local')->exists($path)) {
            return null;
        }
        $binary = Storage::disk('local')->get($path);
        return 'data:image/png;base64,' . base64_encode($binary);
    }

    public function appLogoExists(): bool
    {
        return Storage::disk('local')->exists($this->getAppLogoPath());
    }
}
