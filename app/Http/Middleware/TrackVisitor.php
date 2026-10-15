<?php

namespace App\Http\Middleware;

use App\Models\VisitorLog;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class TrackVisitor
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        return $next($request);
    }

    /**
     * Handle tasks after the response has been sent to the browser (Terminable Middleware).
     */
    public function terminate(Request $request, Response $response): void
    {
        // Only track successful GET requests on public pages
        if ($request->isMethod('GET') && $response->getStatusCode() < 400 && $this->shouldTrack($request)) {
            $this->logVisitor($request);
        }
    }

    /**
     * Determine if the request should be tracked.
     */
    protected function shouldTrack(Request $request): bool
    {
        $path = trim($request->path(), '/');

        // Ignore admin, auth, internal, debug, or asset routes
        if ($request->is('admin*') ||
            $request->is('login*') ||
            $request->is('logout*') ||
            $request->is('register*') ||
            $request->is('password*') ||
            $request->is('up') ||
            $request->is('api*') ||
            $request->is('sanctum*') ||
            $request->is('build*') ||
            $request->is('storage*') ||
            $request->is('images*') ||
            $request->is('_debugbar*')
        ) {
            return false;
        }

        // Ignore static asset file extensions
        $extension = strtolower(pathinfo($path, PATHINFO_EXTENSION));
        if (in_array($extension, ['css', 'js', 'json', 'png', 'jpg', 'jpeg', 'webp', 'svg', 'gif', 'ico', 'woff', 'woff2', 'ttf', 'eot', 'map'])) {
            return false;
        }

        // Ignore common web crawler bots
        $userAgent = (string) $request->userAgent();
        if ($this->isBot($userAgent)) {
            return false;
        }

        return true;
    }

    /**
     * Record visitor information into database.
     */
    protected function logVisitor(Request $request): void
    {
        try {
            $userAgent = (string) $request->userAgent();
            $path = '/' . trim($request->path(), '/');
            if ($path === '//' || $path === '') {
                $path = '/';
            }

            $ip = $request->ip() ?? '127.0.0.1';
            $sessionId = session()->getId() ?: md5($ip . date('Y-m-d'));

            // Detect metadata
            $device = $this->detectDevice($userAgent);
            $browser = $this->detectBrowser($userAgent);
            $platform = $this->detectPlatform($userAgent);
            $pageTitle = $this->derivePageTitle($path);
            $referer = (string) $request->header('referer');

            VisitorLog::create([
                'ip_address'  => $ip,
                'url'         => $path,
                'page_title'  => $pageTitle,
                'method'      => $request->method(),
                'device'      => $device,
                'browser'     => $browser,
                'platform'    => $platform,
                'user_agent'  => substr($userAgent, 0, 500),
                'referer'     => $referer ? substr($referer, 0, 500) : null,
                'session_id'  => $sessionId,
            ]);
        } catch (\Throwable) {
            // Silently ignore logging errors to prevent breaking the public website
        }
    }

    /**
     * Check if user agent is a crawler / bot.
     */
    protected function isBot(string $userAgent): bool
    {
        $botPatterns = [
            'bot', 'crawl', 'spider', 'slurp', 'mediapartners', 'lighthouse',
            'headlesschrome', 'googlebot', 'bingbot', 'yandex', 'duckduckbot',
            'baiduspider', 'twitterbot', 'facebookexternalhit', 'rogerbot',
            'linkedinbot', 'embedly', 'quora link preview', 'showyoubot',
            'outbrain', 'pinterest', 'slackbot', 'vkShare', 'W3C_Validator'
        ];

        $lowerAgent = strtolower($userAgent);
        foreach ($botPatterns as $bot) {
            if (str_contains($lowerAgent, $bot)) {
                return true;
            }
        }

        return false;
    }

    /**
     * Detect Device Type.
     */
    protected function detectDevice(string $userAgent): string
    {
        $lower = strtolower($userAgent);

        if (str_contains($lower, 'ipad') || str_contains($lower, 'tablet') || (str_contains($lower, 'android') && !str_contains($lower, 'mobile'))) {
            return 'Tablet';
        }

        if (str_contains($lower, 'mobile') || str_contains($lower, 'iphone') || str_contains($lower, 'ipod') || str_contains($lower, 'android')) {
            return 'Mobile';
        }

        return 'Desktop';
    }

    /**
     * Detect Browser Name.
     */
    protected function detectBrowser(string $userAgent): string
    {
        $lower = strtolower($userAgent);

        if (str_contains($lower, 'edg') || str_contains($lower, 'edge')) {
            return 'Microsoft Edge';
        }
        if (str_contains($lower, 'opr') || str_contains($lower, 'opera')) {
            return 'Opera';
        }
        if (str_contains($lower, 'samsungbrowser')) {
            return 'Samsung Internet';
        }
        if (str_contains($lower, 'chrome') || str_contains($lower, 'crios')) {
            return 'Google Chrome';
        }
        if (str_contains($lower, 'firefox') || str_contains($lower, 'fxios')) {
            return 'Mozilla Firefox';
        }
        if (str_contains($lower, 'safari') && !str_contains($lower, 'chrome')) {
            return 'Apple Safari';
        }

        return 'Other Browser';
    }

    /**
     * Detect Operating System / Platform.
     */
    protected function detectPlatform(string $userAgent): string
    {
        $lower = strtolower($userAgent);

        if (str_contains($lower, 'windows')) {
            return 'Windows';
        }
        if (str_contains($lower, 'android')) {
            return 'Android';
        }
        if (str_contains($lower, 'iphone') || str_contains($lower, 'ipad') || str_contains($lower, 'ipod')) {
            return 'iOS';
        }
        if (str_contains($lower, 'macintosh') || str_contains($lower, 'mac os x')) {
            return 'macOS';
        }
        if (str_contains($lower, 'linux')) {
            return 'Linux';
        }

        return 'Unknown OS';
    }

    /**
     * Derive user-friendly Page Title from path.
     */
    protected function derivePageTitle(string $path): string
    {
        if ($path === '/' || $path === '') {
            return 'Beranda (Home)';
        }
        if (str_starts_with($path, '/prestasi')) {
            return 'Prestasi Sekolah';
        }
        if (str_starts_with($path, '/tentang') || str_starts_with($path, '/about')) {
            return 'Profil & Tentang Sekolah';
        }
        if (str_starts_with($path, '/berita')) {
            return 'Berita & Artikel';
        }
        if (str_starts_with($path, '/jurusan') || str_starts_with($path, '/programs')) {
            return 'Program Keahlian';
        }
        if (str_starts_with($path, '/waka')) {
            return 'WAKA & Manajemen';
        }
        if (str_starts_with($path, '/guru') || str_starts_with($path, '/teachers')) {
            return 'Guru & Staff';
        }
        if (str_starts_with($path, '/galeri') || str_starts_with($path, '/gallery')) {
            return 'Galeri Sekolah';
        }
        if (str_starts_with($path, '/fasilitas') || str_starts_with($path, '/facilities')) {
            return 'Fasilitas Sekolah';
        }
        if (str_starts_with($path, '/kontak') || str_starts_with($path, '/contact')) {
            return 'Kontak & Hubungi Kami';
        }
        if (str_starts_with($path, '/agenda')) {
            return 'Agenda & Kegiatan';
        }

        return 'Halaman ' . ucfirst(trim($path, '/'));
    }
}
