<?php

namespace App\Filters;

use CodeIgniter\Filters\FilterInterface;
use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;

/**
 * Ustawia nagłówki Cache-Control dla publicznych stron GET.
 *
 * Strona główna : public, max-age=60, s-maxage=300  (CDN: 5 min, przeglądarka: 1 min)
 * Pozostałe     : public, max-age=3600               (artykuły itp.: 1 h)
 *
 * Pomija: admin, assety, obrazki, pliki, cron, AJAX, błędy.
 */
class CacheControl implements FilterInterface
{
    private const SKIP_PREFIXES = [
        '/image/',
        '/file',
        '/download',
        '/tio.',
        '/cron/',
        '/sitemap',
        '/robots',
    ];

    public function before(RequestInterface $request, $arguments = null) {}

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        if ($request->getMethod() !== 'get' || $request->isAJAX()) {
            return;
        }

        if ($response->getStatusCode() !== 200) {
            return;
        }

        $path = '/' . ltrim($request->getUri()->getPath(), '/');

        $adminSlug = env('ADMIN_PANEL_SLUG');
        if ($adminSlug && str_starts_with($path, '/' . $adminSlug)) {
            return;
        }

        foreach (self::SKIP_PREFIXES as $prefix) {
            if (str_starts_with($path, $prefix)) {
                return;
            }
        }

        $isHome = ($path === '/' || preg_match('#^/[a-z]{2}/?$#', $path));

        if ($isHome) {
            $response->setHeader('Cache-Control', 'public, max-age=60, s-maxage=300, stale-while-revalidate=60');
        } else {
            $response->setHeader('Cache-Control', 'public, max-age=3600, stale-while-revalidate=300');
        }
    }
}
