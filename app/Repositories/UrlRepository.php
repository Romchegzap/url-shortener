<?php

namespace App\Repositories;

use App\Models\Url;

class UrlRepository
{
    public function getByOriginalUrl(string $originalUrl): ?Url
    {
        return Url::where('original_url', $originalUrl)->first();
    }
}
