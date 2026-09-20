@extends('layout')

@section('title', 'Tableau de Bord — Opportunités & Matches')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Top Hero / Welcome Banner (Responsive) -->
    <div class="bg-gradient-to-r from-brand-900 via-brand-800 to-indigo-900 rounded-2xl p-4 sm:p-6 text-white shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black tracking-tight">Bonjour {{ $user->name }}</h1>
            <p class="text-brand-100 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                L'IA analyse en continu les opportunités d'emploi par similarité sémantique avec votre profil
                @if($cv)
                    (<span class="font-medium text-white">{{ $cv->parsed_data['titre_professionnel'] ?? 'Développeur' }}</span>).
                @else
                    . <a href="{{ route('profile.show') }}" class="underline font-bold text-white hover:text-brand-200">Téléversez votre CV</a> pour activer le scoring.
                @endif
            </p>
            <!-- Critères de recherche actifs -->
            @if(isset($profile) && ($profile->type_opportunite || $profile->pays || !empty($profile->types_contrat)))
                <div class="mt-3 flex flex-wrap items-center gap-1.5 text-xs">
                    <span class="text-brand-200 font-semibold">Vos critères :</span>
                    @if($profile->type_opportunite)
                        <span class="px-2.5 py-0.5 rounded-lg bg-white/20 text-white font-medium backdrop-blur-xs">
                            Poste : {{ $profile->type_opportunite }}
                        </span>
                    @endif
                    @if($profile->pays)
                        <span class="px-2.5 py-0.5 rounded-lg bg-white/20 text-white font-medium backdrop-blur-xs">
                            Pays : {{ $profile->pays }}
                        </span>
                    @endif
                    @if(!empty($profile->types_contrat))
                        <span class="px-2.5 py-0.5 rounded-lg bg-white/20 text-white font-medium backdrop-blur-xs">
                            Contrat : {{ implode(', ', $profile->types_contrat) }}
                        </span>
                    @endif
                    <a href="{{ route('profile.show') }}#preferences" class="ml-1 text-[11px] text-brand-200 hover:text-white underline font-semibold transition">
                        Modifier &rarr;
                    </a>
                </div>
            @else
                <div class="mt-3">
                    <a href="{{ route('profile.show') }}#preferences" class="inline-flex items-center text-xs text-brand-200 hover:text-white font-semibold underline">
                        Définir vos critères (type d'opportunité, pays, contrat) &rarr;
                    </a>
                </div>
            @endif
        </div>
        <div class="w-full sm:w-auto flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
            <form action="{{ route('opportunites.collect') }}" method="POST" class="w-full sm:w-auto">
                @csrf
                <input type="hidden" name="source_type" value="rss">
                <button type="submit" class="w-full sm:w-auto px-4 py-2.5 rounded-xl bg-white text-brand-900 font-bold text-xs sm:text-sm shadow-sm hover:bg-brand-50 transition flex items-center justify-center space-x-2">
                    <svg class="w-4 h-4 text-brand-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Actualiser la Collecte</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Key Metrics Stats (2x2 on mobile, 4x1 on desktop) -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-2.5 sm:gap-4">
        <div class="bg-white p-3 sm:p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Offres Veillées</p>
                <p class="text-xl sm:text-2xl font-black text-slate-900 mt-0.5">{{ $stats['total_opportunites'] }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
            </div>
        </div>

        <div class="bg-white p-3 sm:p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Matches Forts (>70%)</p>
                <p class="text-xl sm:text-2xl font-black text-emerald-600 mt-0.5">{{ $stats['high_matches'] }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-3 sm:p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Candidatures</p>
                <p class="text-xl sm:text-2xl font-black text-indigo-600 mt-0.5">{{ $stats['candidatures_en_cours'] }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-3 sm:p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Entretiens</p>
                <p class="text-xl sm:text-2xl font-black text-amber-600 mt-0.5">{{ $stats['entretiens'] }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Main Section: Opportunities & Matches -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header & Filter Bar -->
        <div class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-200 flex flex-col sm:flex-row justify-between sm:items-center gap-3 bg-slate-50/60">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Opportunités Recommandées</h2>
                <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">Triées par similarité sémantique et adéquation des compétences.</p>
            </div>
            <!-- Filter Pills -->
            <div class="flex items-center space-x-2 shrink-0">
                <a href="{{ route('dashboard', ['score_min' => 70]) }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ request('score_min') == 70 ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }} transition">
                    Top Matches (>70%)
                </a>
                <a href="{{ route('dashboard') }}" class="px-3 py-1.5 rounded-lg text-xs font-bold {{ !request('score_min') ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }} transition">
                    Toutes
                </a>
            </div>
        </div>

        <!-- Offers List -->
        <div class="divide-y divide-slate-100">
            @forelse($matches as $match)
                @php
                    $opp = $match->opportunite;
                    $score = $match->score_pertinence;
                    $badgeClass = $score >= 75 ? 'bg-emerald-100 text-emerald-800 border-emerald-300' : ($score >= 50 ? 'bg-blue-100 text-blue-800 border-blue-300' : 'bg-slate-100 text-slate-700 border-slate-300');
                @endphp
                <div class="p-4 sm:p-6 hover:bg-slate-50/80 transition flex flex-col md:flex-row gap-4 items-start justify-between">
                    
                    <div class="flex-1 space-y-2.5 w-full">
                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                            <!-- Match Score Chip -->
                            <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-[11px] sm:text-xs font-black border {{ $badgeClass }}">
                                Match {{ number_format($score, 1) }}%
                            </span>

                            <!-- Contract Type -->
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700">
                                {{ $opp->type_contrat }}
                            </span>

                            <!-- Location -->
                            <span class="inline-flex items-center text-[11px] sm:text-xs text-slate-500">
                                <svg class="w-3.5 h-3.5 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                <span class="truncate max-w-[180px] sm:max-w-none">{{ $opp->localisation }}</span>
                            </span>

                            @if($opp->date_publication)
                                <span class="text-[11px] text-slate-400 hidden sm:inline">&bull; {{ $opp->date_publication }}</span>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">
                                {{ $opp->titre }}
                            </h3>
                            <p class="text-xs font-semibold text-brand-700 mt-0.5">
                                {{ $opp->entreprise }}
                            </p>
                        </div>

                        <p class="text-xs text-slate-600 line-clamp-2 leading-relaxed">
                            {{ $opp->description }}
                        </p>

                        <!-- Matching & Missing Skills badges -->
                        <div class="flex flex-wrap gap-1 sm:gap-1.5 pt-0.5">
                            @if(!empty($match->matching_skills))
                                @foreach($match->matching_skills as $skill)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] sm:text-[11px] font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">
                                        {{ $skill }}
                                    </span>
                                @endforeach
                            @endif

                            @if(!empty($match->missing_skills))
                                @foreach(array_slice($match->missing_skills, 0, 3) as $mskill)
                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] sm:text-[11px] font-medium bg-slate-100 text-slate-500">
                                        + {{ $mskill }}
                                    </span>
                                @endforeach
                            @endif
                        </div>

                        @if($match->summary_explanation)
                            <p class="text-[11px] text-slate-500 italic bg-slate-100/70 rounded-lg px-2.5 py-1 inline-block">
                                {{ $match->summary_explanation }}
                            </p>
                        @endif
                    </div>

                    <!-- Actions (Responsive Layout) -->
                    <div class="flex items-center justify-between md:flex-col md:items-end gap-2 shrink-0 w-full md:w-auto pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                        <a href="{{ route('candidatures.generate', $opp->id) }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-sm transition whitespace-nowrap">
                            <span>Préparer Candidature</span>
                            <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </a>

                        <div class="flex items-center space-x-1 shrink-0">
                            <form action="{{ route('matches.status', $match->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="statut" value="{{ $match->statut === 'favori' ? 'nouveau' : 'favori' }}">
                                <button type="submit" class="p-2 rounded-xl border border-slate-200 text-xs {{ $match->statut === 'favori' ? 'bg-amber-50 text-amber-600 border-amber-300' : 'text-slate-500 hover:bg-slate-100' }}" title="Mettre en favori">
                                    <svg class="w-3.5 h-3.5" fill="{{ $match->statut === 'favori' ? 'currentColor' : 'none' }}" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z"></path></svg>
                                </button>
                            </form>

                            <form action="{{ route('matches.status', $match->id) }}" method="POST">
                                @csrf
                                <input type="hidden" name="statut" value="ignore">
                                <button type="submit" class="p-2 rounded-xl border border-slate-200 text-xs text-slate-400 hover:text-rose-600 hover:bg-rose-50 hover:border-rose-200" title="Ignorer cette offre">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                </button>
                            </form>
                        </div>
                    </div>

                </div>
            @empty
                <div class="p-8 sm:p-12 text-center">
                    <p class="text-slate-400 text-sm">Aucune opportunité disponible avec ces critères.</p>
                    <form action="{{ route('opportunites.collect') }}" method="POST" class="mt-3">
                        @csrf
                        <button type="submit" class="text-xs sm:text-sm font-bold text-brand-600 hover:underline">
                            Lancer une collecte d'offres maintenant &rarr;
                        </button>
                    </form>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Kanban Cycle de Vie (Touch-friendly & Responsive with Horizontal Snap on Mobile) -->
    @if($candidatures->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Suivi des Candidatures (Kanban)</h2>
                    <p class="text-[11px] sm:text-xs text-slate-400 md:hidden">Faites glisser horizontalement pour voir toutes les colonnes &rarr;</p>
                </div>
            </div>
            
            <!-- Horizontal Swipeable on Mobile, 4-col Grid on Desktop -->
            <div class="flex md:grid md:grid-cols-4 overflow-x-auto md:overflow-x-visible pb-3 md:pb-0 gap-3 sm:gap-4 snap-x">
                
                <!-- Colonne 1: Brouillons -->
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-200 min-w-[260px] md:min-w-0 flex-1 shrink-0 snap-start">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-slate-600 uppercase tracking-wider">Brouillons</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-slate-200 text-slate-700">
                            {{ $candidatures->where('statut', 'brouillon')->count() }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        @foreach($candidatures->where('statut', 'brouillon') as $cand)
                            <div class="p-3 bg-white rounded-xl border border-slate-200 shadow-xs space-y-1">
                                <p class="text-xs font-bold text-slate-900 leading-snug">{{ $cand->opportunite->titre }}</p>
                                <p class="text-[11px] text-slate-500 font-medium">{{ $cand->opportunite->entreprise }}</p>
                                <div class="pt-1 flex justify-between items-center">
                                    <a href="{{ route('candidatures.generate', $cand->opportunite_id) }}" class="text-[11px] text-brand-600 font-bold hover:underline">Finaliser &rarr;</a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Colonne 2: Validées -->
                <div class="bg-blue-50/50 rounded-xl p-3 border border-blue-100 min-w-[260px] md:min-w-0 flex-1 shrink-0 snap-start">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Validées</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">
                            {{ $candidatures->where('statut', 'validee')->count() }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        @foreach($candidatures->where('statut', 'validee') as $cand)
                            <div class="p-3 bg-white rounded-xl border border-blue-200 shadow-xs space-y-1">
                                <p class="text-xs font-bold text-slate-900 leading-snug">{{ $cand->opportunite->titre }}</p>
                                <p class="text-[11px] text-slate-500 font-medium">{{ $cand->opportunite->entreprise }}</p>
                                <div class="pt-1 flex justify-between items-center">
                                    <form action="{{ route('candidatures.status', $cand->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="statut" value="envoyee">
                                        <button type="submit" class="text-[11px] text-emerald-600 font-bold hover:underline">Marquer Envoyée &rarr;</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Colonne 3: Envoyées -->
                <div class="bg-indigo-50/50 rounded-xl p-3 border border-indigo-100 min-w-[260px] md:min-w-0 flex-1 shrink-0 snap-start">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-indigo-700 uppercase tracking-wider">Envoyées</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-indigo-100 text-indigo-800">
                            {{ $candidatures->where('statut', 'envoyee')->count() }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        @foreach($candidatures->where('statut', 'envoyee') as $cand)
                            <div class="p-3 bg-white rounded-xl border border-indigo-200 shadow-xs space-y-1">
                                <p class="text-xs font-bold text-slate-900 leading-snug">{{ $cand->opportunite->titre }}</p>
                                <p class="text-[11px] text-slate-500 font-medium">{{ $cand->opportunite->entreprise }}</p>
                                <p class="text-[10px] text-slate-400">Envoyé le {{ $cand->date_envoi ? $cand->date_envoi->format('d/m/Y') : 'Récent' }}</p>
                                <div class="pt-1 flex justify-between items-center">
                                    <form action="{{ route('candidatures.status', $cand->id) }}" method="POST">
                                        @csrf
                                        <input type="hidden" name="statut" value="entretien">
                                        <button type="submit" class="text-[11px] text-amber-600 font-bold hover:underline">Entretien décroché &rarr;</button>
                                    </form>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Colonne 4: Entretiens -->
                <div class="bg-amber-50/50 rounded-xl p-3 border border-amber-100 min-w-[260px] md:min-w-0 flex-1 shrink-0 snap-start">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-amber-700 uppercase tracking-wider">Entretiens</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-amber-100 text-amber-800">
                            {{ $candidatures->where('statut', 'entretien')->count() }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        @foreach($candidatures->where('statut', 'entretien') as $cand)
                            <div class="p-3 bg-white rounded-xl border border-amber-200 shadow-xs space-y-1">
                                <p class="text-xs font-bold text-slate-900 leading-snug">{{ $cand->opportunite->titre }}</p>
                                <p class="text-[11px] text-slate-500 font-medium">{{ $cand->opportunite->entreprise }}</p>
                                <span class="inline-block mt-1 px-2 py-0.5 text-[10px] font-bold bg-amber-100 text-amber-800 rounded">
                                    En contact recruteur
                                </span>
                            </div>
                        @endforeach
                    </div>
                </div>

            </div>
        </div>
    @endif

</div>
@endsection
