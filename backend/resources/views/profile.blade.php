@extends('layout')

@section('title', 'Mon Profil & CV — JobMatch AI')

@section('content')
<div class="max-w-5xl mx-auto space-y-4 sm:space-y-6">

    <!-- Header Section -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-3 bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm">
        <div>
            <h1 class="text-xl sm:text-2xl font-black text-slate-900">Mon Profil Candidat</h1>
        </div>
        <div class="flex items-center space-x-2 self-start sm:self-auto">
            <span class="w-2.5 h-2.5 rounded-full {{ $cv ? 'bg-emerald-500' : 'bg-amber-500' }} shrink-0"></span>
            <span class="text-xs sm:text-sm font-bold text-slate-700">{{ $cv ? 'CV Actif' : 'En attente de CV' }}</span>
        </div>
    </div>

    <!-- Upload Box -->
    <div class="bg-white p-4 sm:p-5 rounded-2xl border-2 border-dashed {{ $cv ? 'border-slate-300' : 'border-brand-400 bg-brand-50/20' }} shadow-xs">
        <form action="{{ route('cv.upload') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
            @csrf
            <div class="flex flex-col items-center justify-center text-center">
                <div class="w-10 h-10 rounded-xl bg-brand-50 text-brand-600 flex items-center justify-center font-bold text-lg mb-2">
                    <svg class="w-5 h-5 text-brand-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path></svg>
                </div>
                <h3 class="text-sm sm:text-base font-bold text-slate-800">
                    {{ $cv ? 'Actualiser le CV' : 'Déposer votre CV' }}
                </h3>
                <p class="text-[11px] text-slate-400 mt-0.5">
                    Formats acceptés : PDF, DOCX, TXT
                </p>

                <div class="mt-3 flex flex-col sm:flex-row items-stretch sm:items-center gap-2.5 w-full sm:w-auto max-w-lg">
                    <input type="file" name="cv_file" required accept=".pdf,.docx,.txt" class="block w-full text-xs text-slate-500 file:mr-3 file:py-2 file:px-3.5 file:rounded-xl file:border-0 file:text-xs file:font-bold file:bg-brand-50 file:text-brand-700 hover:file:bg-brand-100 cursor-pointer">
                    <button type="submit" class="w-full sm:w-auto px-5 py-2 rounded-xl bg-brand-600 hover:bg-brand-700 text-white font-bold text-xs shadow-sm transition shrink-0 whitespace-nowrap">
                        Analyser
                    </button>
                </div>
            </div>
        </form>
    </div>

    <!-- Search Preferences Form Card -->
    <div id="preferences" class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm space-y-4">
        <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 pb-3 border-b border-slate-100">
            <div>
                <h2 class="text-base sm:text-lg font-black text-slate-900">Critères de Recherche</h2>
            </div>
            @if($profile && ($profile->type_opportunite || $profile->pays || !empty($profile->types_contrat)))
                <span class="self-start sm:self-auto px-2.5 py-0.5 rounded-full text-[11px] font-bold bg-emerald-50 text-emerald-700 border border-emerald-200 shrink-0">
                    Configurés
                </span>
            @endif
        </div>

        <form action="{{ route('profile.preferences.update') }}" method="POST" class="space-y-4">
            @csrf

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <!-- 1. Type d'opportunité souhaité -->
                <div class="space-y-1.5">
                    <label for="type_opportunite" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Poste ciblé
                    </label>
                    <input type="text" id="type_opportunite" name="type_opportunite" 
                           value="{{ old('type_opportunite', $profile->type_opportunite ?? ($cv->parsed_data['titre_professionnel'] ?? '')) }}"
                           placeholder="Ex: Ingénieur Logiciel, Chef de Projet..."
                           class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:outline-none transition">
                </div>

                <!-- 2. Pays souhaité -->
                <div class="space-y-1.5">
                    <label for="pays" class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                        Pays
                    </label>
                    @php $currentPays = old('pays', $profile->pays ?? 'France'); @endphp
                    <select id="pays" name="pays" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:outline-none transition bg-white">
                        <option value="Tous pays" {{ $currentPays == 'Tous pays' ? 'selected' : '' }}>Tous pays / International</option>
                        <option value="France" {{ $currentPays == 'France' ? 'selected' : '' }}>France</option>
                        <option value="Bénin" {{ $currentPays == 'Bénin' ? 'selected' : '' }}>Bénin</option>
                        <option value="Canada" {{ $currentPays == 'Canada' ? 'selected' : '' }}>Canada</option>
                        <option value="Sénégal" {{ $currentPays == 'Sénégal' ? 'selected' : '' }}>Sénégal</option>
                        <option value="Côte d'Ivoire" {{ $currentPays == "Côte d'Ivoire" ? 'selected' : '' }}>Côte d'Ivoire</option>
                        <option value="Belgique" {{ $currentPays == 'Belgique' ? 'selected' : '' }}>Belgique</option>
                        <option value="Suisse" {{ $currentPays == 'Suisse' ? 'selected' : '' }}>Suisse</option>
                        <option value="Télétravail international" {{ $currentPays == 'Télétravail international' ? 'selected' : '' }}>Télétravail International</option>
                    </select>
                </div>

            </div>

            <!-- 3. Type de contrat -->
            <div class="space-y-2 pt-1">
                <label class="block text-xs font-bold text-slate-700 uppercase tracking-wider">
                    Contrats acceptés
                </label>
                @php
                    $selectedContracts = old('types_contrat', (array)($profile->types_contrat ?? ['CDI', 'FREELANCE']));
                @endphp
                <div class="grid grid-cols-2 sm:grid-cols-3 md:grid-cols-5 gap-2">
                    @foreach([
                        'CDI' => 'CDI',
                        'CDD' => 'CDD',
                        'FREELANCE' => 'Freelance',
                        'STAGE' => 'Stage',
                        'ALTERNANCE' => 'Alternance'
                    ] as $code => $label)
                        <label class="flex items-center p-2.5 rounded-xl border border-slate-200 hover:bg-slate-50 cursor-pointer transition has-[:checked]:bg-brand-50 has-[:checked]:border-brand-400 has-[:checked]:text-brand-900 shadow-2xs">
                            <input type="checkbox" name="types_contrat[]" value="{{ $code }}" 
                                   {{ in_array($code, $selectedContracts) ? 'checked' : '' }}
                                   class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                            <span class="ml-2 text-xs font-bold">{{ $label }}</span>
                        </label>
                    @endforeach
                </div>
            </div>

            <!-- Options Complémentaires : Télétravail & Salaire minimum -->
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 pt-2 border-t border-slate-100 items-center">
                <div class="flex items-center">
                    <input type="checkbox" id="teletravail_only" name="teletravail_only" value="1"
                            {{ old('teletravail_only', $profile->teletravail_only ?? false) ? 'checked' : '' }}
                            class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                    <label for="teletravail_only" class="ml-2 block text-xs font-semibold text-slate-700 cursor-pointer">
                        Télétravail uniquement
                    </label>
                </div>

                <div>
                    <label for="salaire_min" class="block text-[11px] font-bold text-slate-600 uppercase tracking-wider mb-1">
                        Salaire minimum annuel (€ brut)
                    </label>
                    <input type="number" id="salaire_min" name="salaire_min" step="1000"
                            value="{{ old('salaire_min', $profile->salaire_min ?? '') }}"
                            placeholder="Ex: 40000"
                            class="w-full px-3.5 py-2 rounded-xl border border-slate-300 text-xs focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:outline-none transition">
                </div>
            </div>

            <!-- Submit Button -->
            <div class="pt-2 flex justify-end">
                <button type="submit" class="px-5 py-2.5 rounded-xl bg-slate-900 hover:bg-slate-800 text-white font-bold text-xs shadow-xs transition">
                    Enregistrer
                </button>
            </div>

        </form>
    </div>

    <!-- Extracted Profile Data (Responsive Card) -->
    @if($cv && !empty($cv->parsed_data))
        @php $data = $cv->parsed_data; @endphp
        <div class="bg-white rounded-2xl border border-slate-200 shadow-sm divide-y divide-slate-100 overflow-hidden">
            
            <!-- Summary Info -->
            <div class="p-4 sm:p-6 bg-slate-50/60 flex flex-col md:flex-row justify-between gap-3 sm:gap-4">
                <div>
                    <span class="text-[10px] sm:text-xs font-bold text-brand-600 uppercase tracking-wider">Fichier source : {{ $cv->original_filename }}</span>
                    <h2 class="text-lg sm:text-xl font-black text-slate-900 mt-1">{{ $data['nom'] ?? $user->name }}</h2>
                    <p class="text-xs sm:text-sm font-semibold text-slate-700 mt-0.5">{{ $data['titre_professionnel'] ?? 'Titre non spécifié' }}</p>
                </div>
                <div class="text-xs text-slate-500 space-y-1">
                    <p><span class="font-medium text-slate-700">Email :</span> {{ $data['email'] ?? $user->email }}</p>
                    <p><span class="font-medium text-slate-700">Tél :</span> {{ $data['telephone'] ?? 'Non renseigné' }}</p>
                    <p><span class="font-medium text-slate-700">Expérience :</span> ~{{ $data['annees_experience'] ?? 3 }} ans</p>
                </div>
            </div>

            <!-- Skills -->
            <div class="p-4 sm:p-6 space-y-3 sm:space-y-4">
                <div>
                    <h3 class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Compétences Techniques Détectées</h3>
                    <div class="flex flex-wrap gap-1.5 sm:gap-2">
                        @foreach($data['competences_techniques'] ?? [] as $skill)
                            <span class="inline-flex items-center px-2.5 sm:px-3 py-1 rounded-lg text-xs font-bold bg-brand-50 text-brand-700 border border-brand-200">
                                {{ $skill }}
                            </span>
                        @endforeach
                    </div>
                </div>

                @if(!empty($data['competences_soft']))
                    <div class="pt-1 sm:pt-2">
                        <h3 class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2">Soft Skills & Savoir-être</h3>
                        <div class="flex flex-wrap gap-1.5 sm:gap-2">
                            @foreach($data['competences_soft'] as $soft)
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-lg text-xs font-medium bg-slate-100 text-slate-700">
                                    {{ $soft }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

            <!-- Experiences -->
            <div class="p-4 sm:p-6">
                <h3 class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-3 sm:mb-4">Expériences Professionnelles Extraites</h3>
                <div class="space-y-3 sm:space-y-4">
                    @forelse($data['experiences'] ?? [] as $exp)
                        <div class="border-l-2 border-brand-500 pl-3 sm:pl-4 py-1">
                            <h4 class="text-xs sm:text-sm font-bold text-slate-900">{{ $exp['poste'] }}</h4>
                            <p class="text-[11px] sm:text-xs text-slate-500 font-medium">{{ $exp['entreprise'] ?? 'Entreprise' }} &bull; {{ $exp['periode'] ?? 'Période' }}</p>
                            @if(!empty($exp['description']))
                                <p class="text-xs text-slate-600 mt-1 leading-relaxed">{{ $exp['description'] }}</p>
                            @endif
                        </div>
                    @empty
                        <p class="text-xs text-slate-400">Aucune expérience détaillée extraite.</p>
                    @endforelse
                </div>
            </div>

            <!-- Formations & Langues -->
            <div class="p-4 sm:p-6 grid grid-cols-1 md:grid-cols-2 gap-4 sm:gap-6 bg-slate-50/40">
                <div>
                    <h3 class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 sm:mb-3">Formations & Diplômes</h3>
                    <div class="space-y-2">
                        @foreach($data['formations'] ?? [] as $form)
                            <div class="text-xs">
                                <p class="font-bold text-slate-800">{{ $form['diplome'] }}</p>
                                <p class="text-slate-500 text-[11px]">{{ $form['etablissement'] ?? 'Établissement' }} &bull; {{ $form['annee'] ?? '' }}</p>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div>
                    <h3 class="text-[11px] sm:text-xs font-bold text-slate-500 uppercase tracking-wider mb-2 sm:mb-3">Langues</h3>
                    <div class="flex flex-wrap gap-1.5 sm:gap-2">
                        @foreach($data['langues'] ?? ['Français'] as $langue)
                            <span class="inline-flex items-center px-2 py-0.5 rounded text-xs bg-white border border-slate-200 text-slate-700">
                                {{ $langue }}
                            </span>
                        @endforeach
                    </div>
                </div>
            </div>

        </div>
    @endif

</div>
@endsection
