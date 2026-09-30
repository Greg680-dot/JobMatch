@extends('layout')

@section('title', 'Statistiques & Audience — JobMatch AI')

@section('content')
<div class="max-w-7xl mx-auto space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div class="flex items-center space-x-2">
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">Audience & Statistiques</h1>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200">En direct</span>
            <span class="px-2.5 py-0.5 rounded-full text-[10px] font-bold bg-indigo-50 text-indigo-700 border border-indigo-200">Accès Administrateur</span>
        </div>
    </div>

    <!-- 4 KPI Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        <!-- KPI 1 : Total Visites -->
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Visites</span>
            <div class="flex items-baseline space-x-2 mt-1">
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($totalVisites, 0, ',', ' ') }}</span>
                <span class="text-xs font-semibold text-emerald-600">+{{ $visitesAujourdhui }} auj.</span>
            </div>
        </div>

        <!-- KPI 2 : Visiteurs Uniques -->
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Visiteurs Uniques</span>
            <div class="flex items-baseline space-x-2 mt-1">
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ number_format($visiteursUniques, 0, ',', ' ') }}</span>
                <span class="text-xs font-semibold text-emerald-600">+{{ $visiteursUniquesToday }} auj.</span>
            </div>
        </div>

        <!-- KPI 3 : Mobile vs PC -->
        @php
            $mobileCount = $deviceStats->firstWhere('device_type', 'Smartphone')?->total ?? 0;
            $mobilePercent = $totalVisites > 0 ? round(($mobileCount / $totalVisites) * 100) : 0;
            $desktopPercent = 100 - $mobilePercent;
        @endphp
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Smartphones</span>
            <div class="flex items-baseline space-x-2 mt-1">
                <span class="text-2xl sm:text-3xl font-black text-brand-600">{{ $mobilePercent }}%</span>
                <span class="text-xs font-semibold text-slate-500">PC : {{ $desktopPercent }}%</span>
            </div>
        </div>

        <!-- KPI 4 : Pays d'origine -->
        <div class="bg-white p-3.5 sm:p-4 rounded-2xl border border-slate-200 shadow-xs">
            <span class="text-[11px] font-bold text-slate-400 uppercase tracking-wider block">Pays Détectés</span>
            <div class="flex items-baseline space-x-2 mt-1">
                <span class="text-2xl sm:text-3xl font-black text-slate-900">{{ count($countryStats) }}</span>
                <span class="text-xs font-semibold text-slate-500">régions</span>
            </div>
        </div>
    </div>
    </div>

    <!-- Main Grid: Pays & Appareils -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">

        <!-- Colonne 1 : Téléphone vs Ordinateur & Détail Technique -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-base font-black text-slate-900">Appareils</h2>
            </div>

            <!-- Jauges de répartition appareils -->
            <div class="space-y-3.5">
                @forelse($deviceStats as $device)
                    @php
                        $pct = round(($device->total / $totalDevices) * 100, 1);
                        $color = match($device->device_type) {
                            'Smartphone' => 'bg-brand-600',
                            'Ordinateur' => 'bg-indigo-600',
                            'Tablette' => 'bg-amber-500',
                            default => 'bg-slate-500'
                        };
                    @endphp
                    <div>
                        <div class="flex justify-between text-xs font-bold text-slate-700 mb-1">
                            <span class="flex items-center space-x-1.5">
                                <span>{{ $device->device_type }}</span>
                            </span>
                            <span>{{ $device->total }} visites ({{ $pct }}%)</span>
                        </div>
                        <div class="w-full bg-slate-100 h-2.5 rounded-full overflow-hidden">
                            <div class="{{ $color }} h-2.5 rounded-full transition-all duration-500" style="width: {{ $pct }}%"></div>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">Aucune donnée d'appareil enregistrée pour le moment.</p>
                @endforelse
            </div>

            <!-- Systèmes d'exploitation & Navigateurs -->
            <div class="grid grid-cols-2 gap-4 pt-4 border-t border-slate-100">
                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Systèmes (OS)</h3>
                    <div class="space-y-1.5">
                        @foreach($osStats as $os)
                            <div class="flex justify-between text-xs">
                                <span class="font-medium text-slate-700">{{ $os->os }}</span>
                                <span class="font-bold text-slate-500">{{ $os->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Navigateurs</h3>
                    <div class="space-y-1.5">
                        @foreach($browserStats as $br)
                            <div class="flex justify-between text-xs">
                                <span class="font-medium text-slate-700">{{ $br->browser }}</span>
                                <span class="font-bold text-slate-500">{{ $br->total }}</span>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne 2 : Répartition par Pays -->
        <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-5">
            <div class="flex items-center justify-between pb-3 border-b border-slate-100">
                <h2 class="text-base font-black text-slate-900">Pays</h2>
                <span class="text-xs font-bold text-brand-600 bg-brand-50 px-2.5 py-1 rounded-lg">
                    {{ count($countryStats) }} pays
                </span>
            </div>

            <div class="space-y-2.5">
                @forelse($countryStats as $c)
                    @php
                        $pct = $totalVisites > 0 ? round(($c->total / $totalVisites) * 100, 1) : 0;
                    @endphp
                    <div class="flex items-center justify-between p-2.5 rounded-xl hover:bg-slate-50 transition border border-slate-100">
                        <div class="flex items-center space-x-3">
                            <span class="w-8 h-6 rounded bg-slate-100 text-slate-700 font-bold text-[10px] flex items-center justify-center border border-slate-200">
                                {{ $c->country_code ?: '--' }}
                            </span>
                            <p class="text-xs font-bold text-slate-900">{{ $c->country }}</p>
                        </div>
                        <div class="text-right">
                            <span class="text-xs font-bold text-slate-900">{{ $c->total }}</span>
                            <span class="text-[11px] text-slate-400 block">{{ $pct }}%</span>
                        </div>
                    </div>
                @empty
                    <p class="text-xs text-slate-400">Aucun pays enregistré pour le moment.</p>
                @endforelse
            </div>
        </div>

    </div>

    <!-- Fréquence des Visites (7 derniers jours) -->
    <div class="bg-white p-5 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="pb-3 border-b border-slate-100">
            <h2 class="text-base font-black text-slate-900">Fréquence (7 jours)</h2>
        </div>

        @php
            $maxDaily = max(array_column($dailyVisits, 'count') ?: [1]);
            $maxDaily = max($maxDaily, 1);
        @endphp
        <div class="grid grid-cols-7 gap-2 sm:gap-4 items-end h-40 pt-6">
            @foreach($dailyVisits as $day)
                @php
                    $heightPercent = round(($day['count'] / $maxDaily) * 100);
                    $heightPercent = max(8, $heightPercent);
                @endphp
                <div class="flex flex-col items-center h-full justify-end">
                    <span class="text-[11px] font-bold text-slate-700 mb-1">{{ $day['count'] }}</span>
                    <div class="w-full max-w-[40px] bg-brand-500 hover:bg-brand-600 rounded-t-xl transition-all duration-300" style="height: {{ $heightPercent }}%"></div>
                    <span class="text-[10px] text-slate-400 font-medium mt-2 text-center truncate w-full">{{ $day['date'] }}</span>
                </div>
            @endforeach
        </div>
    </div>

    <!-- Journal des Dernières Visites (Temps Réel) -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        <div class="p-4 sm:p-5 border-b border-slate-100 flex justify-between items-center">
            <h2 class="text-base font-black text-slate-900">Visites Récentes</h2>
            <span class="text-xs font-bold text-slate-400">{{ $totalVisites }} total</span>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-left text-xs">
                <thead class="bg-slate-50 text-[11px] text-slate-400 uppercase font-bold tracking-wider border-b border-slate-100">
                    <tr>
                        <th class="py-3 px-4">Heure</th>
                        <th class="py-3 px-4">Pays</th>
                        <th class="py-3 px-4">Appareil</th>
                        <th class="py-3 px-4">OS / Navigateur</th>
                        <th class="py-3 px-4">Page Consultée</th>
                        <th class="py-3 px-4">Utilisateur</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-100">
                    @forelse($recentVisites as $v)
                        <tr class="hover:bg-slate-50/70 transition">
                            <td class="py-3 px-4 text-slate-500 whitespace-nowrap">
                                {{ $v->created_at->format('d/m H:i:s') }}
                            </td>
                            <td class="py-3 px-4 font-bold text-slate-800 whitespace-nowrap">
                                <span class="inline-flex items-center space-x-1.5">
                                    <span class="px-1.5 py-0.5 rounded bg-slate-100 text-[10px] text-slate-600 font-mono">{{ $v->country_code ?: 'BJ' }}</span>
                                    <span>{{ $v->country }}</span>
                                </span>
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[11px] font-bold {{ $v->device_type === 'Smartphone' ? 'bg-brand-50 text-brand-700' : 'bg-slate-100 text-slate-700' }}">
                                    {{ $v->device_type }}
                                </span>
                            </td>
                            <td class="py-3 px-4 text-slate-600 whitespace-nowrap">
                                {{ $v->os }} &bull; {{ $v->browser }}
                            </td>
                            <td class="py-3 px-4 font-mono text-[11px] text-slate-700 truncate max-w-[150px]">
                                {{ $v->path }}
                            </td>
                            <td class="py-3 px-4 whitespace-nowrap">
                                @if($v->user)
                                    <span class="font-bold text-brand-700">{{ $v->user->name }}</span>
                                @else
                                    <span class="text-slate-400">Visiteur anonyme</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="py-6 text-center text-xs text-slate-400">
                                Aucune visite enregistrée pour le moment.
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Guide Outils Externes (Google Analytics & Clarity) -->
    <div class="bg-gradient-to-r from-slate-900 to-indigo-950 p-5 sm:p-6 rounded-2xl text-white shadow-md space-y-3">
        <h3 class="text-sm sm:text-base font-bold text-white">Intégration d'Outils d'Audience Professionnels (Optionnel)</h3>
        <p class="text-xs text-slate-300 leading-relaxed max-w-3xl">
            En plus de ce tableau de bord interne direct, vous pouvez connecter en 1 clic les outils professionnels de mesure d'audience en ajoutant simplement leur identifiant dans vos variables d'environnement Render :
        </p>
        <div class="grid grid-cols-1 md:grid-cols-2 gap-3 pt-1 text-xs">
            <div class="bg-white/10 p-3 rounded-xl border border-white/10">
                <span class="font-bold text-brand-300 block mb-0.5">Google Analytics 4 (GA4)</span>
                <p class="text-slate-300 text-[11px]">
                    Ajoutez la variable <code class="bg-black/30 px-1 py-0.5 rounded text-amber-300">GA_TRACKING_ID=G-XXXXXXXXXX</code> dans Render pour obtenir les rapports complets Google (durée de session, acquisition, entonnoirs).
                </p>
            </div>
            <div class="bg-white/10 p-3 rounded-xl border border-white/10">
                <span class="font-bold text-indigo-300 block mb-0.5">Microsoft Clarity (100% Gratuit)</span>
                <p class="text-slate-300 text-[11px]">
                    Ajoutez la variable <code class="bg-black/30 px-1 py-0.5 rounded text-amber-300">CLARITY_PROJECT_ID=XXXXXX</code> dans Render pour voir les vidéos des écrans de vos visiteurs et les cartes de chaleur sur smartphone et PC.
                </p>
            </div>
        </div>
    </div>

</div>
@endsection