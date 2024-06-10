<?php

namespace App\Http\Controllers;

use App\Exceptions\UrlIsDangerousException;
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
        try {
            $url = $service->findOrCreate($request->get('originalUrl'));

            return Inertia::render('Shortener', [
                'shortUrl'    => url($service::getShortUrlRoute($url->short_hash)),
                'originalUrl' => $url->original_url
            ]);
        } catch (UrlIsDangerousException $exception) {
            return Inertia::render('Shortener')->with([
                'errors' => [
                    'originalUrl' => 'URL is dangerous, please try another one'
                ]
            ]);
        }
    }

    public function show($hash, UrlService $service): \Illuminate\Foundation\Application|JsonResponse|\Illuminate\Routing\Redirector|\Illuminate\Contracts\Foundation\Application|RedirectResponse
    {
        try {
            $url = $service->findOriginalUrlByShortHash($hash);
            return redirect($url);
        } catch (Throwable $exception) {
            Log::error('UrlController@show', ['error' => $exception->getMessage(), 'hash' => $hash]);
            return response()->json(['status' => 'error', 'message' => 'Oops something wrong happened.']);
        }
    }
}
