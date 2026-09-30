<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Visite;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AnalyticsController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        // 1. KPIs globaux
        $totalVisites = Visite::count();
        $visitesAujourdhui = Visite::where('created_at', '>=', $today)->count();
        $visiteursUniques = Visite::distinct('ip_address')->count('ip_address');
        $visiteursUniquesToday = Visite::where('created_at', '>=', $today)->distinct('ip_address')->count('ip_address');

        // 2. Répartition par Type d'Appareil
        $deviceStats = Visite::select('device_type', DB::raw('count(*) as total'))
            ->groupBy('device_type')
            ->orderByDesc('total')
            ->get();
        $totalDevices = $deviceStats->sum('total') ?: 1;

        // 3. Répartition par Système d'Exploitation (OS)
        $osStats = Visite::select('os', DB::raw('count(*) as total'))
            ->groupBy('os')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        // 4. Répartition par Navigateur
        $browserStats = Visite::select('browser', DB::raw('count(*) as total'))
            ->groupBy('browser')
            ->orderByDesc('total')
            ->limit(6)
            ->get();

        // 5. Répartition par Pays
        $countryStats = Visite::select('country', 'country_code', DB::raw('count(*) as total'))
            ->groupBy('country', 'country_code')
            ->orderByDesc('total')
            ->limit(10)
            ->get();

        // 6. Fréquence des visites par jour (7 derniers jours)
        $dailyVisits = [];
        for ($i = 6; $i >= 0; $i--) {
            $day = Carbon::today()->subDays($i);
            $dayStart = $day->copy()->startOfDay();
            $dayEnd = $day->copy()->endOfDay();
            $count = Visite::whereBetween('created_at', [$dayStart, $dayEnd])->count();
            $dailyVisits[] = [
                'date' => $day->locale('fr')->isoFormat('ddd D MMM'),
                'count' => $count,
            ];
        }

        // 7. Pages les plus consultées
        $topPages = Visite::select('path', DB::raw('count(*) as total'))
            ->groupBy('path')
            ->orderByDesc('total')
            ->limit(8)
            ->get();

        // 8. Journal des 20 dernières visites en direct
        $recentVisites = Visite::with('user')
            ->orderByDesc('id')
            ->limit(20)
            ->get();

        return view('analytics', compact(
            'totalVisites',
            'visitesAujourdhui',
            'visiteursUniques',
            'visiteursUniquesToday',
            'deviceStats',
            'totalDevices',
            'osStats',
            'browserStats',
            'countryStats',
            'dailyVisits',
            'topPages',
            'recentVisites'
        ));
    }
}