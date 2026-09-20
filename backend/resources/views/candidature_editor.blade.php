@extends('layout')

@section('title', 'Préparation de Candidature — ' . $opportunite->titre)

@section('content')
<div class="space-y-4 sm:space-y-6">

    <!-- Top Bar Navigation (Responsive) -->
    <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2">
        <a href="{{ route('dashboard') }}" class="inline-flex items-center text-xs font-bold text-slate-500 hover:text-slate-900 transition">
            &larr; Retour aux Opportunités
        </a>
        <div class="flex items-center space-x-2">
            <span class="text-xs font-semibold text-slate-400">Statut actuel :</span>
            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-slate-100 text-slate-700 uppercase">
                {{ $candidature->statut }}
            </span>
        </div>
    </div>

    <!-- Main Two-Column Layout (Stacked on Mobile/Tablet, Side-by-side on Desktop) -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-4 sm:gap-6">

        <!-- Left Column: Job Details & ATS CV Recommendations (lg:col-span-5) -->
        <div class="lg:col-span-5 space-y-4 sm:space-y-5">
            
            <!-- Job Card -->
            <div class="bg-white p-4 sm:p-5 rounded-2xl border border-slate-200 shadow-sm space-y-3">
                <div class="flex items-center justify-between">
                    <span class="text-xs font-bold text-brand-600 uppercase tracking-wider">{{ $opportunite->entreprise }}</span>
                    @if($match)
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-black bg-emerald-100 text-emerald-800 border border-emerald-200">
                            Match {{ number_format($match->score_pertinence, 1) }}%
                        </span>
                    @endif
                </div>

                <h2 class="text-base sm:text-lg font-black text-slate-900 leading-snug">{{ $opportunite->titre }}</h2>

                <div class="flex flex-wrap gap-1.5 sm:gap-2 text-xs text-slate-500">
                    <span class="bg-slate-100 px-2 py-0.5 rounded font-bold text-slate-700">{{ $opportunite->type_contrat }}</span>
                    <span>{{ $opportunite->localisation }}</span>
                    @if($opportunite->url_source)
                        <a href="{{ $opportunite->url_source }}" target="_blank" class="text-brand-600 hover:underline font-bold flex items-center">
                            <span>Offre originale</span>
                            <svg class="w-3 h-3 ml-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path></svg>
                        </a>
                    @endif
                </div>

                <div class="pt-2 border-t border-slate-100">
                    <h4 class="text-[11px] font-bold text-slate-500 uppercase tracking-wider mb-1">Extrait de l'offre</h4>
                    <p class="text-xs text-slate-600 leading-relaxed max-h-40 sm:max-h-48 overflow-y-auto bg-slate-50 p-3 rounded-xl border border-slate-100">
                        {{ $opportunite->description }}
                    </p>
                </div>
            </div>

            <!-- ATS CV Adaptation Recommendations -->
            <div class="bg-gradient-to-br from-indigo-50 to-blue-50/50 p-4 sm:p-5 rounded-2xl border border-indigo-100 shadow-sm space-y-3">
                <div class="flex items-center space-x-2">
                    <h3 class="text-xs sm:text-sm font-black text-indigo-950 uppercase tracking-wider">Conseils d'Adaptation ATS</h3>
                </div>
                <p class="text-[11px] sm:text-xs text-indigo-800/80">
                    Ajustements suggérés pour maximiser vos chances face aux filtres de présélection automatique :
                </p>

                <ul class="space-y-2 text-xs text-indigo-950">
                    @forelse($candidature->cv_adaptation_tips ?? [] as $tip)
                        <li class="flex items-start space-x-2 bg-white/80 p-2.5 rounded-xl border border-indigo-100 shadow-xs">
                            <svg class="w-3.5 h-3.5 text-indigo-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            <span class="leading-relaxed">{{ $tip }}</span>
                        </li>
                    @empty
                        <li class="text-xs text-slate-500">Mettez en avant l'intitulé exact du poste dans l'en-tête de votre CV.</li>
                    @endforelse
                </ul>

                @if(!empty($candidature->suggested_skills))
                    <div class="pt-1">
                        <span class="text-[11px] font-bold text-indigo-900 block mb-1">Mots-clés stratégiques :</span>
                        <div class="flex flex-wrap gap-1.5">
                            @foreach($candidature->suggested_skills as $s)
                                <span class="px-2 py-0.5 rounded text-[10px] sm:text-[11px] font-bold bg-white text-indigo-700 border border-indigo-200">
                                    {{ $s }}
                                </span>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>

        </div>

        <!-- Right Column: Interactive Letter Editor (lg:col-span-7) -->
        <div class="lg:col-span-7">
            <div class="bg-white p-4 sm:p-6 rounded-2xl border border-slate-200 shadow-sm space-y-4">
                
                <div class="flex flex-col sm:flex-row justify-between sm:items-center gap-2 pb-3 border-b border-slate-100">
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-slate-900">Lettre de Motivation Personnalisée</h2>
                        <p class="text-[11px] sm:text-xs text-slate-500">Vous pouvez ajuster le texte librement avant de valider votre envoi.</p>
                    </div>

                    <!-- Quick Copy Button (Touch-friendly) -->
                    <button type="button" onclick="copyLetter()" id="copyBtn" class="self-start sm:self-auto inline-flex items-center px-3 py-1.5 rounded-xl border border-slate-200 bg-slate-50 text-xs font-bold text-slate-700 hover:bg-slate-100 transition shrink-0">
                        <svg class="w-3.5 h-3.5 mr-1 text-slate-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                        <span>Copier le texte</span>
                    </button>
                </div>

                <form action="{{ route('candidatures.update', $candidature->id) }}" method="POST" class="space-y-4">
                    @csrf
                    @method('PUT')

                    <!-- Email Subject Line -->
                    <div>
                        <label class="block text-[11px] sm:text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Objet de l'Email</label>
                        <input type="text" name="objet_email" id="emailSubject" value="{{ old('objet_email', $candidature->objet_email) }}" class="w-full px-3.5 py-2.5 text-xs sm:text-sm rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">
                    </div>

                    <!-- Cover Letter Textarea -->
                    <div>
                        <label class="block text-[11px] sm:text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Corps de la Lettre</label>
                        <textarea name="lettre_motivation" id="letterBody" rows="12" class="w-full p-3 sm:p-4 text-xs sm:text-sm text-slate-800 font-mono leading-relaxed rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:outline-none transition">{{ old('lettre_motivation', $candidature->lettre_motivation) }}</textarea>
                    </div>

                    <!-- Status Selector & Notes -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3 sm:gap-4 pt-1">
                        <div>
                            <label class="block text-[11px] sm:text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Statut dans le Kanban</label>
                            <select name="statut" class="w-full px-3 py-2.5 text-xs font-semibold rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:outline-none">
                                <option value="brouillon" {{ $candidature->statut == 'brouillon' ? 'selected' : '' }}>Brouillon</option>
                                <option value="validee" {{ $candidature->statut == 'validee' ? 'selected' : '' }}>Validée (Prête)</option>
                                <option value="envoyee" {{ $candidature->statut == 'envoyee' ? 'selected' : '' }}>Envoyée</option>
                                <option value="entretien" {{ $candidature->statut == 'entretien' ? 'selected' : '' }}>Entretien Décroché</option>
                                <option value="refusee" {{ $candidature->statut == 'refusee' ? 'selected' : '' }}>Refusée</option>
                                <option value="acceptee" {{ $candidature->statut == 'acceptee' ? 'selected' : '' }}>Offre Acceptée</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-[11px] sm:text-xs font-bold text-slate-600 uppercase tracking-wider mb-1">Notes Personnelles</label>
                            <input type="text" name="notes_candidat" placeholder="Ex: Contact RH LinkedIn..." value="{{ old('notes_candidat', $candidature->notes_candidat) }}" class="w-full px-3 py-2.5 text-xs rounded-xl border border-slate-300 focus:ring-2 focus:ring-brand-500 focus:outline-none">
                        </div>
                    </div>

                    <!-- Action Bar (Stacked on Mobile, Flex on Desktop) -->
                    <div class="pt-3 border-t border-slate-100 flex flex-col sm:flex-row items-stretch sm:items-center justify-between gap-2.5">
                        
                        <!-- Open in mailto link -->
                        <a href="#" id="mailtoLink" onclick="openMailto(event)" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2.5 rounded-xl border border-slate-300 bg-white hover:bg-slate-50 text-slate-700 text-xs font-bold transition shadow-xs">
                            <svg class="w-4 h-4 mr-1.5 text-brand-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                            <span>Ouvrir dans ma messagerie</span>
                        </a>

                        <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-5 py-2.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-md transition">
                            Enregistrer & Mettre à jour &rarr;
                        </button>
                    </div>

                </form>

            </div>
        </div>

    </div>

</div>

<script>
    function copyLetter() {
        const text = document.getElementById('letterBody').value;
        navigator.clipboard.writeText(text).then(() => {
            const btn = document.getElementById('copyBtn');
            btn.classList.add('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
            btn.innerHTML = 'Copié !';
            setTimeout(() => {
                btn.classList.remove('bg-emerald-50', 'text-emerald-700', 'border-emerald-300');
                btn.innerHTML = `
                    <svg class="w-3.5 h-3.5 mr-1 text-slate-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 5H6a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2v-1M8 5a2 2 0 002 2h2a2 2 0 002-2M8 5a2 2 0 012-2h2a2 2 0 012 2m0 0h2a2 2 0 012 2v3m2 4H10m0 0l3-3m-3 3l3 3"></path></svg>
                    <span>Copier le texte</span>
                `;
            }, 2500);
        });
    }

    function openMailto(event) {
        event.preventDefault();
        const subject = encodeURIComponent(document.getElementById('emailSubject').value);
        const body = encodeURIComponent(document.getElementById('letterBody').value);
        window.location.href = `mailto:?subject=${subject}&body=${body}`;
    }
</script>
@endsection
