@extends('layout')

@section('title', 'Suivi de mes Candidatures — JobMatch AI')

@section('content')
<div class="space-y-6">

    <!-- Page Header & Action Bar -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 pb-2 border-b border-slate-200">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900 tracking-tight">Suivi de mes Candidatures</h1>
            <p class="text-xs sm:text-sm text-slate-500 mt-0.5">
                Consultez, gérez et suivez l'avancement de vos candidatures déposées, en attente de réponse et entretiens.
            </p>
        </div>
        <div class="flex items-center space-x-2 shrink-0">
            <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition">
                <span>Explorer les opportunités</span>
                <svg class="w-3.5 h-3.5 ml-1.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
            </a>
        </div>
    </div>

    <!-- 4 KPI Metrics Cards -->
    <div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
        
        <!-- KPI 1: Candidatures Déposées -->
        <a href="{{ route('candidatures.index', ['tab' => 'deposees']) }}" class="p-4 sm:p-5 rounded-2xl bg-white border {{ $activeTab === 'deposees' ? 'border-emerald-500 ring-2 ring-emerald-500/20 shadow-sm' : 'border-slate-200 hover:border-slate-300' }} shadow-2xs transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Déposées</span>
                <div class="w-8 h-8 rounded-xl bg-emerald-50 text-emerald-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"></path></svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-emerald-600 transition">
                {{ $counts['deposees'] }}
            </div>
            <p class="text-[11px] text-slate-400 mt-1 font-medium">Envoyées aux recruteurs</p>
        </a>

        <!-- KPI 2: En Attente de Réponse -->
        <a href="{{ route('candidatures.index', ['tab' => 'en_attente']) }}" class="p-4 sm:p-5 rounded-2xl bg-white border {{ $activeTab === 'en_attente' ? 'border-indigo-500 ring-2 ring-indigo-500/20 shadow-sm' : 'border-slate-200 hover:border-slate-300' }} shadow-2xs transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">En Attente</span>
                <div class="w-8 h-8 rounded-xl bg-indigo-50 text-indigo-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-indigo-600 transition">
                {{ $counts['en_attente'] }}
            </div>
            <p class="text-[11px] text-slate-400 mt-1 font-medium">En attente de retour</p>
        </a>

        <!-- KPI 3: Entretiens Décrochés -->
        <a href="{{ route('candidatures.index', ['tab' => 'entretien']) }}" class="p-4 sm:p-5 rounded-2xl bg-white border {{ $activeTab === 'entretien' ? 'border-amber-500 ring-2 ring-amber-500/20 shadow-sm' : 'border-slate-200 hover:border-slate-300' }} shadow-2xs transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Entretiens</span>
                <div class="w-8 h-8 rounded-xl bg-amber-50 text-amber-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-amber-600 transition">
                {{ $counts['entretiens'] }}
            </div>
            <p class="text-[11px] text-slate-400 mt-1 font-medium">Échanges obtenus</p>
        </a>

        <!-- KPI 4: Brouillons & Prêtes -->
        <a href="{{ route('candidatures.index', ['tab' => 'brouillon']) }}" class="p-4 sm:p-5 rounded-2xl bg-white border {{ $activeTab === 'brouillon' ? 'border-blue-500 ring-2 ring-blue-500/20 shadow-sm' : 'border-slate-200 hover:border-slate-300' }} shadow-2xs transition group">
            <div class="flex items-center justify-between mb-2">
                <span class="text-xs font-bold text-slate-500 uppercase tracking-wider">Brouillons</span>
                <div class="w-8 h-8 rounded-xl bg-slate-100 text-slate-600 flex items-center justify-center shrink-0">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                </div>
            </div>
            <div class="text-2xl sm:text-3xl font-black text-slate-900 group-hover:text-blue-600 transition">
                {{ $counts['brouillons'] }}
            </div>
            <p class="text-[11px] text-slate-400 mt-1 font-medium">À finaliser ou déposer</p>
        </a>

    </div>

    <!-- Filter Pills & Search Bar -->
    <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 space-y-3">
        <div class="flex flex-col md:flex-row justify-between items-stretch md:items-center gap-3">
            
            <!-- Filter Tabs -->
            <div class="flex flex-wrap gap-1.5 text-xs font-bold">
                <a href="{{ route('candidatures.index', array_merge(request()->query(), ['tab' => 'all'])) }}" 
                   class="px-3 py-1.5 rounded-xl transition {{ $activeTab === 'all' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Toutes ({{ $counts['total'] }})
                </a>
                <a href="{{ route('candidatures.index', array_merge(request()->query(), ['tab' => 'deposees'])) }}" 
                   class="px-3 py-1.5 rounded-xl transition {{ $activeTab === 'deposees' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Déposées ({{ $counts['deposees'] }})
                </a>
                <a href="{{ route('candidatures.index', array_merge(request()->query(), ['tab' => 'en_attente'])) }}" 
                   class="px-3 py-1.5 rounded-xl transition {{ $activeTab === 'en_attente' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    En attente de réponse ({{ $counts['en_attente'] }})
                </a>
                <a href="{{ route('candidatures.index', array_merge(request()->query(), ['tab' => 'entretien'])) }}" 
                   class="px-3 py-1.5 rounded-xl transition {{ $activeTab === 'entretien' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Entretiens ({{ $counts['entretiens'] }})
                </a>
                <a href="{{ route('candidatures.index', array_merge(request()->query(), ['tab' => 'brouillon'])) }}" 
                   class="px-3 py-1.5 rounded-xl transition {{ $activeTab === 'brouillon' ? 'bg-brand-600 text-white shadow-xs' : 'bg-slate-100 text-slate-600 hover:bg-slate-200' }}">
                    Brouillons ({{ $counts['brouillons'] }})
                </a>
            </div>

            <!-- Search Filter Form -->
            <form method="GET" action="{{ route('candidatures.index') }}" class="flex items-center gap-2">
                <input type="hidden" name="tab" value="{{ $activeTab }}">
                <div class="relative flex-1 md:w-64">
                    <input type="text" name="q" value="{{ $search }}" placeholder="Poste, entreprise..." 
                           class="w-full pl-8 pr-3 py-1.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                    <svg class="w-3.5 h-3.5 text-slate-400 absolute left-2.5 top-2.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                </div>
                @if($search)
                    <a href="{{ route('candidatures.index', ['tab' => $activeTab]) }}" class="text-xs text-slate-400 hover:text-slate-600 font-semibold">Effacer</a>
                @endif
                <button type="submit" class="px-3 py-1.5 rounded-xl bg-slate-800 hover:bg-slate-900 text-white text-xs font-bold transition">
                    Filtrer
                </button>
            </form>

        </div>
    </div>

    <!-- Candidatures List -->
    <div class="space-y-3">
        @forelse($candidatures as $cand)
            @php
                $opp = $cand->opportunite;
                $match = $cand->match;
                
                // Color & label mapping for status
                $statusConfig = [
                    'brouillon' => ['label' => 'Brouillon', 'bg' => 'bg-slate-100', 'text' => 'text-slate-700', 'border' => 'border-slate-200'],
                    'validee'   => ['label' => 'Validée (Prête)', 'bg' => 'bg-blue-50', 'text' => 'text-blue-700', 'border' => 'border-blue-200'],
                    'envoyee'   => ['label' => 'Déposée / Envoyée', 'bg' => 'bg-emerald-50', 'text' => 'text-emerald-700', 'border' => 'border-emerald-200'],
                    'relancee'  => ['label' => 'Relancée', 'bg' => 'bg-cyan-50', 'text' => 'text-cyan-700', 'border' => 'border-cyan-200'],
                    'entretien' => ['label' => 'Entretien Décroché', 'bg' => 'bg-amber-50', 'text' => 'text-amber-800', 'border' => 'border-amber-200'],
                    'refusee'   => ['label' => 'Non retenue', 'bg' => 'bg-rose-50', 'text' => 'text-rose-700', 'border' => 'border-rose-200'],
                    'acceptee'  => ['label' => 'Offre Acceptée', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-900', 'border' => 'border-emerald-300'],
                ];
                $cfg = $statusConfig[$cand->statut] ?? $statusConfig['brouillon'];
            @endphp

            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-4 sm:p-5 hover:border-slate-300 transition space-y-3">
                
                <div class="flex flex-col md:flex-row md:items-center justify-between gap-3 pb-3 border-b border-slate-100">
                    <div>
                        <div class="flex flex-wrap items-center gap-2 mb-1">
                            <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">{{ $opp->entreprise }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-xs text-slate-500 font-medium">{{ $opp->type_contrat }}</span>
                            <span class="text-slate-300">&bull;</span>
                            <span class="text-xs text-slate-500 font-medium">{{ $opp->localisation }}</span>
                            @if($match)
                                <span class="px-2 py-0.5 rounded-full text-[10px] font-black bg-emerald-50 text-emerald-700 border border-emerald-200">
                                    Match {{ number_format($match->score_pertinence, 1) }}%
                                </span>
                            @endif
                        </div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900 leading-snug">
                            {{ $opp->titre }}
                        </h2>
                    </div>

                    <!-- Status Badge -->
                    <div class="flex items-center space-x-2 shrink-0 self-start md:self-auto">
                        <span class="px-3 py-1 rounded-full text-xs font-bold border {{ $cfg['bg'] }} {{ $cfg['text'] }} {{ $cfg['border'] }}">
                            {{ $cfg['label'] }}
                        </span>
                    </div>
                </div>

                <!-- Timeline & Details Row -->
                <div class="grid grid-cols-1 md:grid-cols-3 gap-3 text-xs">
                    
                    <!-- Date & State Info -->
                    <div class="space-y-1">
                        @if($cand->date_envoi)
                            <div class="flex items-center text-slate-700 font-medium">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-emerald-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                <span>Déposée le {{ $cand->date_envoi->format('d/m/Y') }}</span>
                            </div>
                            <p class="text-[11px] text-slate-400 pl-5">
                                Envoyée {{ $cand->date_envoi->diffForHumans() }}
                            </p>
                        @else
                            <div class="flex items-center text-slate-500">
                                <svg class="w-3.5 h-3.5 mr-1.5 text-slate-400 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                <span>Créée le {{ $cand->created_at->format('d/m/Y') }}</span>
                            </div>
                            <p class="text-[11px] text-slate-400 pl-5">Non encore déposée auprès du recruteur</p>
                        @endif
                    </div>

                    <!-- Subject & Letter Snippet -->
                    <div class="md:col-span-2 space-y-1 bg-slate-50 p-2.5 rounded-xl border border-slate-100">
                        <p class="font-bold text-slate-800 text-[11px] truncate">
                            <span class="text-slate-400 font-normal">Objet :</span> {{ $cand->objet_email }}
                        </p>
                        <p class="text-[11px] text-slate-500 line-clamp-1 italic">
                            {{ Str::limit($cand->lettre_motivation, 140) }}
                        </p>
                        @if($cand->notes_candidat)
                            <p class="text-[11px] text-brand-700 font-medium pt-0.5 border-t border-slate-200/60">
                                Note : {{ $cand->notes_candidat }}
                            </p>
                        @endif
                    </div>

                </div>

                <!-- Action Controls Bar -->
                <div class="pt-2 flex flex-col sm:flex-row justify-between items-stretch sm:items-center gap-2 border-t border-slate-100">
                    
                    <!-- Quick Status Transition Form -->
                    <form action="{{ route('candidatures.status', $cand->id) }}" method="POST" class="flex flex-wrap items-center gap-1.5">
                        @csrf
                        <span class="text-[11px] font-bold text-slate-500 mr-1 hidden sm:inline">Changer statut :</span>
                        
                        @if(in_array($cand->statut, ['brouillon', 'validee']))
                            <input type="hidden" name="statut" value="envoyee">
                            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-700 bg-emerald-50 hover:bg-emerald-100 border border-emerald-200 transition">
                                Marquer comme Déposée &rarr;
                            </button>
                        @elseif($cand->statut === 'envoyee')
                            <input type="hidden" name="statut" value="entretien">
                            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition">
                                Entretien obtenu &rarr;
                            </button>
                            <button type="submit" formaction="{{ route('candidatures.status', $cand->id) }}" onclick="this.form.statut.value='relancee'" class="px-3 py-1.5 rounded-xl text-xs font-bold text-cyan-700 bg-cyan-50 hover:bg-cyan-100 border border-cyan-200 transition">
                                Relancer
                            </button>
                        @elseif($cand->statut === 'relancee')
                            <input type="hidden" name="statut" value="entretien">
                            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold text-amber-800 bg-amber-50 hover:bg-amber-100 border border-amber-200 transition">
                                Entretien obtenu &rarr;
                            </button>
                        @elseif($cand->statut === 'entretien')
                            <input type="hidden" name="statut" value="acceptee">
                            <button type="submit" class="px-3 py-1.5 rounded-xl text-xs font-bold text-emerald-800 bg-emerald-100 hover:bg-emerald-200 border border-emerald-300 transition">
                                Offre acceptée &rarr;
                            </button>
                        @endif
                    </form>

                    <!-- Primary Action Buttons -->
                    <div class="flex items-center space-x-2 shrink-0">
                        <a href="{{ route('candidatures.generate', $cand->opportunite_id) }}" class="inline-flex items-center px-3.5 py-1.5 rounded-xl bg-slate-100 hover:bg-slate-200 text-slate-800 text-xs font-bold transition">
                            <span>Voir & Ajuster la lettre</span>
                            <svg class="w-3 h-3 ml-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </a>

                        @if($opp->url_source)
                            <a href="{{ $opp->url_source }}" target="_blank" class="p-1.5 rounded-xl border border-slate-200 text-slate-400 hover:text-brand-600 hover:bg-slate-50 transition" title="Consulter l'offre source">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                            </a>
                        @endif
                    </div>

                </div>

            </div>
        @empty
            <div class="bg-white rounded-2xl border border-slate-200 shadow-sm p-8 sm:p-12 text-center space-y-3">
                <div class="w-12 h-12 rounded-2xl bg-slate-100 text-slate-400 flex items-center justify-center mx-auto">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                </div>
                <h3 class="text-base font-bold text-slate-800">Aucune candidature trouvée</h3>
                <p class="text-xs text-slate-500 max-w-md mx-auto">
                    @if($search)
                        Aucun résultat pour votre recherche « {{ $search }} ».
                    @else
                        Vous n'avez pas encore de candidature dans cette catégorie. Découvrez des offres compatibles et préparez votre dossier en 1 clic.
                    @endif
                </p>
                <div class="pt-2">
                    <a href="{{ route('dashboard') }}" class="inline-flex items-center px-4 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm transition">
                        Explorer les offres compatibles &rarr;
                    </a>
                </div>
            </div>
        @endforelse
    </div>

</div>
@endsection