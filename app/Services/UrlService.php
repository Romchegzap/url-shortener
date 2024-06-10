<?php

namespace App\Services;

use App\Exceptions\UniqueHashMaxTriesException;
use App\Models\Url;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Cache;
use Str;

class UrlService
{
    public function getUniqueHash(): string
    {
        $try = 0;
        $maxTries = 10000;
        while (true) {
            $hash = Str::random(6);
            if (!Url::where('short_hash', $hash)->exists()) {
                break;
            }

            if ($try > $maxTries) {
                throw new UniqueHashMaxTriesException();
            }

            $try++;
        }

        return $hash;
    }

    public function getCacheKey(string $shortHash): string
    {
        return "url.$shortHash";
    }

    public function cacheUrl(Url $url): void
    {
        Cache::forever($this->getCacheKey($url->short_hash), $url->original_url);
    }

    public function isUrlSafe(string $url): bool
    {
        $client = new Client();

        $apiKey = config('google.safebrowsing.api_key');
        $clientId = config('google.safebrowsing.client_id');
        $clientVersion = config('google.safebrowsing.client_version');
        $response = $client->post("https://safebrowsing.googleapis.com/v4/threatMatches:find?key=$apiKey", [
            'json' => [
                'client'     => [
                    'clientId'      => $clientId,
                    'clientVersion' => $clientVersion
                ],
                'threatInfo' => [
                    'threatTypes'      => ["MALWARE", "SOCIAL_ENGINEERING"],
                    'platformTypes'    => ["ANY_PLATFORM"],
                    'threatEntryTypes' => ["URL"],
                    'threatEntries'    => [
                        ['url' => $url]
                    ]
                ]
            ]
        ]);

        $result = json_decode($response->getBody(), true);
        return empty($result['matches']);
    }

    public function isUrlDangerous(string $url): bool
    {
        return !$this->isUrlSafe($url);
    }

    public static function getShortUrlRoute(string $hash): string
    {
        return self::getShortUrlPrefix() . '/' . $hash;
    }

    public static function getShortUrlPrefix(): ?string
    {
        return config('settings.short_url_prefix');
    }
}
