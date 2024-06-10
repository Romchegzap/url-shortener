<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUrlRequest;
use App\Models\Url;
use App\Repositories\UrlRepository;
use App\Services\UrlService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Inertia\Inertia;
use Throwable;

class UrlController extends Controller
{
    public function __construct(private UrlRepository $urlRepository)
    {
    }

    public function index(): \Inertia\Response
    {
        return Inertia::render('Shortener');
    }

    public function store(StoreUrlRequest $request, UrlService $service): \Inertia\Response
    {
        $url = $request->get('originalUrl');
        $existingUrl = $this->urlRepository->getByOriginalUrl($url);

        if ($existingUrl) {
            return Inertia::render('Shortener', [
                'shortUrl'    => url($service::getShortUrlRoute($existingUrl->short_hash)),
                'originalUrl' => $existingUrl->original_url
            ]);
        }

        if ($service->isUrlDangerous($url)) {
            return Inertia::render('Shortener')->with([
                'errors' => [
                    'originalUrl' => 'URL is dangerous, please try another one'
                ]
            ]);
        }

        $hash = $service->getUniqueHash();

        $url = Url::create([
            'original_url' => $url,
            'short_hash'   => $hash,
        ]);

        $service->cacheUrl($url);

        return Inertia::render('Shortener', [
            'shortUrl'    => url($service::getShortUrlRoute($url->short_hash)),
            'originalUrl' => $url->original_url
        ]);
    }

    public function show($hash, UrlService $service): \Illuminate\Foundation\Application|JsonResponse|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        try {
            $cachedOriginalUrl = Cache::get($service->getCacheKey($hash));

            if ($cachedOriginalUrl) {
                return redirect($cachedOriginalUrl);
            }

            $url = Url::where('short_hash', $hash)->firstOrFail();
            if ($url) {
                $service->cacheUrl($url);
            }

            return redirect($url->original_url);
        } catch (Throwable $exception) {
            Log::error('UrlController@show', ['error' => $exception->getMessage(), 'hash' => $hash]);
            return response()->json(['status' => 'error', 'message' => 'Oops something wrong happened.']);
        }
    }
}
