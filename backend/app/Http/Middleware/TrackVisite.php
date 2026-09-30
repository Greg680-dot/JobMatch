<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Visite;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class TrackVisite
{
    protected array $countryMap = [
        'BJ' => 'Bénin',
        'FR' => 'France',
        'CI' => 'Côte d\'Ivoire',
        'SN' => 'Sénégal',
        'TG' => 'Togo',
        'CM' => 'Cameroun',
        'BF' => 'Burkina Faso',
        'NE' => 'Niger',
        'ML' => 'Mali',
        'GN' => 'Guinée',
        'CA' => 'Canada',
        'US' => 'États-Unis',
        'BE' => 'Belgique',
        'CH' => 'Suisse',
        'MA' => 'Maroc',
        'TN' => 'Tunisie',
        'DZ' => 'Algérie',
        'GB' => 'Royaume-Uni',
        'DE' => 'Allemagne',
        'NG' => 'Nigéria',
        'GH' => 'Ghana',
        'RW' => 'Rwanda',
        'CD' => 'RDC',
        'CG' => 'Congo',
        'GA' => 'Gabon',
    ];

    public function handle(Request $request, Closure $next): Response
    {
        $response = $next($request);

        $path = $request->path();
        if ($this->shouldIgnore($request, $path)) {
            return $response;
        }

        try {
            $ua = (string)$request->userAgent();
            $ip = (string)$request->ip();

            $deviceType = $this->detectDevice($ua);
            $os = $this->detectOS($ua);
            $browser = $this->detectBrowser($ua);

            [$country, $countryCode, $city] = $this->detectLocation($request, $ip);

            Visite::create([
                'ip_address' => substr($ip, 0, 45),
                'country' => $country,
                'country_code' => $countryCode,
                'city' => $city,
                'device_type' => $deviceType,
                'os' => $os,
                'browser' => $browser,
                'path' => '/' . ltrim($path, '/'),
                'user_id' => $request->user()?->id,
                'user_agent' => substr($ua, 0, 500),
            ]);
        } catch (Throwable $e) {
            Log::debug('TrackVisite error: ' . $e->getMessage());
        }

        return $response;
    }

    protected function shouldIgnore(Request $request, string $path): bool
    {
        if ($request->isMethodSafe() === false && !$request->isMethod('GET')) {
            return true;
        }

        if (preg_match('/\.(css|js|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|map)$/i', $path)) {
            return true;
        }

        if (str_starts_with($path, 'up') || str_starts_with($path, 'api/')) {
            return true;
        }

        $ua = strtolower((string)$request->userAgent());
        if (preg_match('/(bot|crawl|spider|slurp|facebookexternalhit|uptimerobot)/i', $ua)) {
            return true;
        }

        return false;
    }

    protected function detectDevice(string $ua): string
    {
        if (preg_match('/(tablet|ipad|playbook|silk)|(android(?!.*mobi))/i', $ua)) {
            return 'Tablette';
        }
        if (preg_match('/(mobile|iphone|ipod|android.*mobile|blackberry|iemobile|opera mini|webos)/i', $ua)) {
            return 'Smartphone';
        }
        return 'Ordinateur';
    }

    protected function detectOS(string $ua): string
    {
        if (preg_match('/windows/i', $ua)) return 'Windows';
        if (preg_match('/android/i', $ua)) return 'Android';
        if (preg_match('/iphone|ipad|ipod/i', $ua)) return 'iOS';
        if (preg_match('/macintosh|mac os x/i', $ua)) return 'macOS';
        if (preg_match('/linux/i', $ua)) return 'Linux';
        return 'Autre';
    }

    protected function detectBrowser(string $ua): string
    {
        if (preg_match('/edg/i', $ua)) return 'Edge';
        if (preg_match('/chrome|crios/i', $ua) && !preg_match('/opr|opera/i', $ua)) return 'Chrome';
        if (preg_match('/firefox|fxios/i', $ua)) return 'Firefox';
        if (preg_match('/safari/i', $ua) && !preg_match('/chrome|crios|android/i', $ua)) return 'Safari';
        if (preg_match('/opr|opera/i', $ua)) return 'Opera';
        return 'Autre';
    }

    protected function detectLocation(Request $request, string $ip): array
    {
        $cfCountry = strtoupper(trim((string)$request->header('CF-IPCountry')));
        if (!empty($cfCountry) && strlen($cfCountry) === 2 && $cfCountry !== 'XX' && $cfCountry !== 'T1') {
            $countryName = $this->countryMap[$cfCountry] ?? $cfCountry;
            return [$countryName, $cfCountry, null];
        }

        if (in_array($ip, ['127.0.0.1', '::1']) || str_starts_with($ip, '192.168.') || str_starts_with($ip, '10.') || str_starts_with($ip, '172.')) {
            return ['Bénin (Local)', 'BJ', 'Cotonou'];
        }

        return Cache::remember("geoip_{$ip}", 86400 * 30, function () use ($ip) {
            try {
                $res = Http::timeout(1.5)->get("http://ip-api.com/json/{$ip}?fields=status,country,countryCode,city");
                if ($res->successful() && ($res->json()['status'] ?? '') === 'success') {
                    $c = $res->json()['country'] ?? 'Inconnu';
                    $cc = $res->json()['countryCode'] ?? null;
                    $city = $res->json()['city'] ?? null;
                    return [$c, $cc, $city];
                }
            } catch (Throwable) {}

            return ['Inconnu', null, null];
        });
    }
}