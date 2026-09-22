@extends('layout')

@section('title', 'Tableau de Bord — Opportunités & Matches')

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Top Hero / Welcome Banner -->
    <div class="bg-gradient-to-r from-brand-900 via-brand-800 to-indigo-900 rounded-2xl p-4 sm:p-6 text-white shadow-md flex flex-col md:flex-row justify-between items-start md:items-center gap-4">
        <div>
            <h1 class="text-xl sm:text-2xl font-black tracking-tight">Bonjour {{ $user->name }}</h1>
            <p class="text-brand-100 text-xs sm:text-sm mt-1 max-w-2xl leading-relaxed">
                L'IA analyse en temps réel les opportunités d'emploi réelles au Bénin, en Afrique de l'Ouest et en télétravail international par similarité avec votre profil
                @if($cv)
                    (<span class="font-medium text-white">{{ $cv->parsed_data['titre_professionnel'] ?? 'Ingénieur Logiciel' }}</span>).
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
                        Définir vos critères (type d'opportunité, Bénin / Afrique, contrat) &rarr;
                    </a>
                </div>
            @endif
        </div>

        <!-- Formulaire de Collecte Multi-Sources (Bénin, Afrique, International) -->
        <div class="w-full md:w-auto bg-white/10 backdrop-blur-md p-2.5 rounded-xl border border-white/15">
            <form action="{{ route('opportunites.collect') }}" method="POST" class="flex flex-col sm:flex-row items-stretch sm:items-center gap-2">
                @csrf
                <select name="source_type" class="px-3 py-2 rounded-lg bg-white text-slate-900 font-semibold text-xs border-0 focus:ring-2 focus:ring-brand-400 focus:outline-none">
                    <option value="all">Toutes sources (Bénin + Afrique + Remote)</option>
                    <option value="benin" {{ request('region') == 'benin' ? 'selected' : '' }}>Bénin (Novojob Bénin, ANPE, ESNs)</option>
                    <option value="afrique" {{ request('region') == 'afrique' ? 'selected' : '' }}>Afrique de l'Ouest (Sénégal, CI, Togo, ReliefWeb)</option>
                    <option value="remote" {{ request('region') == 'remote' ? 'selected' : '' }}>Télétravail & Remote Africa</option>
                </select>
                <button type="submit" class="px-3.5 py-2 rounded-lg bg-emerald-500 hover:bg-emerald-600 text-white font-bold text-xs shadow-xs transition flex items-center justify-center space-x-1.5 whitespace-nowrap">
                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path></svg>
                    <span>Lancer la Collecte</span>
                </button>
            </form>
        </div>
    </div>

    <!-- Key Metrics Stats -->
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
                <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Matches Forts (&gt;70%)</p>
                <p class="text-xl sm:text-2xl font-black text-emerald-600 mt-0.5">{{ $stats['high_matches'] }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-3 sm:p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Bénin &amp; Afrique</p>
                <p class="text-xl sm:text-2xl font-black text-indigo-600 mt-0.5">{{ $stats['afrique_count'] ?? $stats['total_opportunites'] }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            </div>
        </div>

        <div class="bg-white p-3 sm:p-4 rounded-xl border border-slate-200 shadow-xs flex items-center justify-between">
            <div>
                <p class="text-[10px] sm:text-xs font-semibold text-slate-500 uppercase tracking-wider">Candidatures</p>
                <p class="text-xl sm:text-2xl font-black text-amber-600 mt-0.5">{{ $stats['candidatures_en_cours'] }}</p>
            </div>
            <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
            </div>
        </div>
    </div>

    <!-- Main Section: Opportunities & Matches -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm overflow-hidden">
        
        <!-- Header & Filter Bar -->
        <div class="px-4 sm:px-6 py-3.5 sm:py-4 border-b border-slate-200 flex flex-col lg:flex-row justify-between lg:items-center gap-3 bg-slate-50/60">
            <div>
                <h2 class="text-base sm:text-lg font-bold text-slate-900">Opportunités Recommandées</h2>
                <p class="text-[11px] sm:text-xs text-slate-500 mt-0.5">Vraies offres analysées par similarité sémantique et adéquation des compétences.</p>
            </div>

            <!-- Filtres par Région & Critères -->
            <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                @php $activeRegion = request('region', 'all'); @endphp
                <a href="{{ route('dashboard', ['region' => 'all', 'score_min' => request('score_min')]) }}" 
                   class="px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-bold {{ $activeRegion === 'all' ? 'bg-slate-900 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }} transition">
                    Toutes
                </a>
                <a href="{{ route('dashboard', ['region' => 'benin', 'score_min' => request('score_min')]) }}" 
                   class="px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-bold {{ $activeRegion === 'benin' ? 'bg-brand-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }} transition">
                    Bénin
                </a>
                <a href="{{ route('dashboard', ['region' => 'afrique', 'score_min' => request('score_min')]) }}" 
                   class="px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-bold {{ $activeRegion === 'afrique' ? 'bg-brand-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }} transition">
                    Afrique de l'Ouest
                </a>
                <a href="{{ route('dashboard', ['region' => 'remote', 'score_min' => request('score_min')]) }}" 
                   class="px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-bold {{ $activeRegion === 'remote' ? 'bg-brand-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }} transition">
                    Télétravail
                </a>
                <a href="{{ route('dashboard', ['score_min' => request('score_min') == 70 ? null : 70, 'region' => $activeRegion]) }}" 
                   class="px-2.5 sm:px-3 py-1.5 rounded-lg text-xs font-bold {{ request('score_min') == 70 ? 'bg-emerald-600 text-white shadow-xs' : 'bg-white border border-slate-200 text-slate-700 hover:bg-slate-100' }} transition">
                    Matches &gt;70%
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
                    
                    // Détection zone géographique pour badge clair
                    $isBenin = str_contains($opp->localisation, 'Bénin') || str_contains($opp->localisation, 'Cotonou') || str_contains($opp->localisation, 'Calavi');
                    $isAfrica = str_contains($opp->localisation, 'Sénégal') || str_contains($opp->localisation, 'Côte d\'Ivoire') || str_contains($opp->localisation, 'Togo') || str_contains($opp->localisation, 'Dakar') || str_contains($opp->localisation, 'Abidjan') || str_contains($opp->localisation, 'Afrique');
                @endphp
                <div class="p-4 sm:p-6 hover:bg-slate-50/80 transition flex flex-col md:flex-row gap-4 items-start justify-between">
                    
                    <div class="flex-1 space-y-2.5 w-full">
                        <div class="flex flex-wrap items-center gap-1.5 sm:gap-2">
                            <!-- Match Score Chip -->
                            <span class="inline-flex items-center px-2 sm:px-2.5 py-0.5 rounded-full text-[11px] sm:text-xs font-black border {{ $badgeClass }}">
                                Match {{ number_format($score, 1) }}%
                            </span>

                            <!-- Zone géographique Badge -->
                            @if($isBenin)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-amber-50 text-amber-800 border border-amber-200">
                                    Bénin
                                </span>
                            @elseif($isAfrica)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-indigo-50 text-indigo-800 border border-indigo-200">
                                    Afrique
                                </span>
                            @elseif($opp->teletravail)
                                <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-purple-50 text-purple-800 border border-purple-200">
                                    Remote
                                </span>
                            @endif

                            <!-- Contract Type -->
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-[11px] font-bold bg-slate-100 text-slate-700">
                                {{ $opp->type_contrat }}
                            </span>

                            <!-- Location -->
                            <span class="inline-flex items-center text-[11px] sm:text-xs text-slate-500">
                                <svg class="w-3.5 h-3.5 mr-1 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path></svg>
                                <span class="truncate max-w-[200px] sm:max-w-none">{{ $opp->localisation }}</span>
                            </span>

                            @if($opp->salaire_indicatif)
                                <span class="inline-flex items-center text-[11px] font-bold text-slate-700 bg-slate-100 px-2 py-0.5 rounded">
                                    {{ number_format($opp->salaire_indicatif, 0, ',', ' ') }} FCFA/an
                                </span>
                            @endif
                        </div>

                        <div>
                            <h3 class="text-sm sm:text-base font-bold text-slate-900 leading-snug">
                                {{ $opp->titre }}
                            </h3>
                            <div class="flex items-center space-x-2 mt-0.5">
                                <span class="text-xs font-semibold text-brand-700">
                                    {{ $opp->entreprise }}
                                </span>
                                @if($opp->url_source)
                                    <a href="{{ $opp->url_source }}" target="_blank" rel="noopener noreferrer" class="text-[11px] text-slate-400 hover:text-brand-600 inline-flex items-center space-x-0.5" title="Consulter l'annonce originale">
                                        <span>Consulter l'offre</span>
                                        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                                    </a>
                                @endif
                            </div>
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

                    <!-- Actions -->
                    <div class="flex items-center justify-between md:flex-col md:items-end gap-2 shrink-0 w-full md:w-auto pt-3 md:pt-0 border-t md:border-t-0 border-slate-100">
                        <a href="{{ route('candidatures.generate', $opp->id) }}" class="flex-1 md:flex-none inline-flex items-center justify-center px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-sm transition whitespace-nowrap">
                            <span>Postuler / Lettre IA</span>
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
                    <p class="text-slate-400 text-sm">Aucune opportunité trouvée avec ces filtres.</p>
                    <form action="{{ route('opportunites.collect') }}" method="POST" class="mt-3">
                        @csrf
                        <input type="hidden" name="source_type" value="all">
                        <button type="submit" class="text-xs sm:text-sm font-bold text-brand-600 hover:underline">
                            Lancer une collecte d'offres réelles maintenant &rarr;
                        </button>
                    </form>
                </div>
            @endforelse
        </div>
    </div>

    <!-- Kanban Suivi des Candidatures -->
    @if($candidatures->isNotEmpty())
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-6">
            <div class="flex items-center justify-between mb-4">
                <div>
                    <h2 class="text-base sm:text-lg font-bold text-slate-900">Suivi des Candidatures (Kanban)</h2>
                    <p class="text-[11px] sm:text-xs text-slate-400 md:hidden">Faites glisser horizontalement pour voir toutes les colonnes &rarr;</p>
                </div>
                <a href="{{ route('candidatures.index') }}" class="text-xs font-bold text-brand-600 hover:underline">
                    Vue Détaillée &rarr;
                </a>
            </div>
            
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
                        @forelse($candidatures->where('statut', 'brouillon') as $cand)
                            <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-2xs space-y-1.5">
                                <p class="text-xs font-bold text-slate-900 leading-snug">{{ $cand->opportunite->titre }}</p>
                                <p class="text-[11px] text-brand-700 font-semibold">{{ $cand->opportunite->entreprise }}</p>
                                <div class="pt-2 flex justify-between items-center border-t border-slate-100">
                                    <span class="text-[10px] text-slate-400">{{ $cand->updated_at->diffForHumans() }}</span>
                                    <a href="{{ route('candidatures.generate', $cand->opportunite->id) }}" class="text-[11px] font-bold text-brand-600 hover:underline">Éditer &rarr;</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-[11px] text-slate-400 italic py-2 text-center">Aucun brouillon</p>
                        @endforelse
                    </div>
                </div>

                <!-- Colonne 2: Envoyées -->
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-200 min-w-[260px] md:min-w-0 flex-1 shrink-0 snap-start">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-blue-700 uppercase tracking-wider">Envoyées</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-blue-100 text-blue-800">
                            {{ $candidatures->where('statut', 'envoyee')->count() }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        @forelse($candidatures->where('statut', 'envoyee') as $cand)
                            <div class="bg-white p-3 rounded-lg border border-blue-100 shadow-2xs space-y-1.5">
                                <p class="text-xs font-bold text-slate-900 leading-snug">{{ $cand->opportunite->titre }}</p>
                                <p class="text-[11px] text-brand-700 font-semibold">{{ $cand->opportunite->entreprise }}</p>
                                <div class="pt-2 flex justify-between items-center border-t border-slate-100">
                                    <span class="text-[10px] text-slate-400">{{ $cand->date_envoi ? 'Le ' . date('d/m/Y', strtotime($cand->date_envoi)) : '' }}</span>
                                    <a href="{{ route('candidatures.generate', $cand->opportunite->id) }}" class="text-[11px] font-bold text-blue-600 hover:underline">Détails &rarr;</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-[11px] text-slate-400 italic py-2 text-center">Aucune envoyée</p>
                        @endforelse
                    </div>
                </div>

                <!-- Colonne 3: Entretiens -->
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-200 min-w-[260px] md:min-w-0 flex-1 shrink-0 snap-start">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-emerald-700 uppercase tracking-wider">Entretiens</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-emerald-100 text-emerald-800">
                            {{ $candidatures->where('statut', 'entretien')->count() }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        @forelse($candidatures->where('statut', 'entretien') as $cand)
                            <div class="bg-white p-3 rounded-lg border border-emerald-200 shadow-2xs space-y-1.5 bg-emerald-50/20">
                                <p class="text-xs font-bold text-slate-900 leading-snug">{{ $cand->opportunite->titre }}</p>
                                <p class="text-[11px] text-brand-700 font-semibold">{{ $cand->opportunite->entreprise }}</p>
                                @if($cand->notes_candidat)
                                    <p class="text-[10px] text-emerald-700 font-medium line-clamp-2 bg-emerald-50 p-1.5 rounded">
                                        {{ $cand->notes_candidat }}
                                    </p>
                                @endif
                                <div class="pt-2 flex justify-between items-center border-t border-slate-100">
                                    <span class="text-[10px] text-slate-400">Étape clé</span>
                                    <a href="{{ route('candidatures.generate', $cand->opportunite->id) }}" class="text-[11px] font-bold text-emerald-700 hover:underline">Voir &rarr;</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-[11px] text-slate-400 italic py-2 text-center">Aucun entretien</p>
                        @endforelse
                    </div>
                </div>

                <!-- Colonne 4: Validées / Relancées -->
                <div class="bg-slate-50 rounded-xl p-3 border border-slate-200 min-w-[260px] md:min-w-0 flex-1 shrink-0 snap-start">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-xs font-bold text-purple-700 uppercase tracking-wider">Autres</span>
                        <span class="text-xs font-bold px-2 py-0.5 rounded-full bg-purple-100 text-purple-800">
                            {{ $candidatures->whereIn('statut', ['validee', 'relancee', 'acceptee'])->count() }}
                        </span>
                    </div>
                    <div class="space-y-2">
                        @forelse($candidatures->whereIn('statut', ['validee', 'relancee', 'acceptee']) as $cand)
                            <div class="bg-white p-3 rounded-lg border border-slate-200 shadow-2xs space-y-1.5">
                                <p class="text-xs font-bold text-slate-900 leading-snug">{{ $cand->opportunite->titre }}</p>
                                <p class="text-[11px] text-brand-700 font-semibold">{{ $cand->opportunite->entreprise }}</p>
                                <div class="pt-2 flex justify-between items-center border-t border-slate-100">
                                    <span class="text-[10px] uppercase font-bold text-slate-500">{{ $cand->statut }}</span>
                                    <a href="{{ route('candidatures.generate', $cand->opportunite->id) }}" class="text-[11px] font-bold text-brand-600 hover:underline">Voir &rarr;</a>
                                </div>
                            </div>
                        @empty
                            <p class="text-[11px] text-slate-400 italic py-2 text-center">Aucune</p>
                        @endforelse
                    </div>
                </div>

            </div>
        </div>
    @endif

</div>
@endsection
