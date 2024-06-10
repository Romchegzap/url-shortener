<?php

namespace App\Repositories;

use App\Models\Url;

class UrlRepository
{
    public function getHashByUrl(string $url): int
    {
        //Can be done via md5 and binary column type for even more lightweight for big data tables
        return crc32($url);
    }

    public function getByOriginalUrl(string $originalUrl): ?Url
    {
        return Url::where('original_url_hash', $this->getHashByUrl($originalUrl))->where('original_url', $originalUrl)->first();
    }

    public function createUrl(string $originalUrl, string $shortHash): ?Url
    {
        return Url::create([
            'original_url'      => $originalUrl,
            'original_url_hash' => $this->getHashByUrl($originalUrl),
            'short_hash'        => $shortHash,
        ]);
    }
}
