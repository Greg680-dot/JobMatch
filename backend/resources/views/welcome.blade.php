<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>JobMatch AI — Trouvez l'opportunité idéale & postulez plus vite</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    colors: {
                        brand: {
                            50: '#eff6ff',
                            100: '#dbeafe',
                            500: '#3b82f6',
                            600: '#2563eb',
                            700: '#1d4ed8',
                            800: '#1e40af',
                            900: '#1e3a8a',
                        }
                    }
                }
            }
        }
    </script>
</head>
<body class="min-h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-brand-500 selection:text-white">

    <!-- Public Navbar (Responsive) -->
    <header class="bg-white/85 backdrop-blur-md border-b border-slate-200 sticky top-0 z-50">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14 sm:h-16 items-center">
                <!-- Logo -->
                <a href="{{ route('home') }}" class="flex items-center space-x-2">
                    <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-brand-900 via-brand-700 to-indigo-600 flex items-center justify-center text-white font-black text-sm sm:text-lg shadow-md shadow-brand-500/20 shrink-0">
                        JM
                    </div>
                    <div>
                        <span class="text-base sm:text-xl font-black tracking-tight text-brand-900">JobMatch <span class="text-brand-600">AI</span></span>
                        <span class="hidden sm:block text-[9px] text-slate-400 uppercase tracking-widest font-semibold">Plateforme de Candidature</span>
                    </div>
                </a>

                <!-- Navigation Links & Auth Buttons -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    @auth
                        <a href="{{ route('dashboard') }}" class="px-3 sm:px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-sm transition">
                            <span>Mon Dashboard</span> &rarr;
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-2.5 sm:px-4 py-2 rounded-xl text-xs font-bold text-slate-700 hover:text-slate-900 hover:bg-slate-100 transition">
                            Se connecter
                        </a>
                        <a href="{{ route('register') }}" class="px-3 sm:px-4 py-2 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-md shadow-brand-600/20 transition whitespace-nowrap">
                            Créer un compte
                        </a>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Main Content -->
    <main class="flex-1">

        <!-- Flash Messages -->
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 mt-4">
            @if(session('success'))
                <div class="p-3 sm:p-4 text-xs sm:text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200">
                    {{ session('success') }}
                </div>
            @endif
        </div>

        <!-- Hero Section (Side-by-side on Desktop: text and video demo on the same line) -->
        <section class="relative pt-6 sm:pt-12 lg:pt-14 pb-14 sm:pb-20 overflow-hidden">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
                
                <div class="grid grid-cols-1 lg:grid-cols-12 gap-8 lg:gap-10 xl:gap-12 items-center">
                    
                    <!-- Left Column: Copywriting & CTA -->
                    <div class="lg:col-span-5 text-left space-y-5">
                        <h1 class="text-3xl sm:text-4xl xl:text-5xl font-black text-slate-950 tracking-tight leading-[1.15]">
                            Trouvez l'opportunité qui vous correspond. <br class="hidden sm:inline">
                            <span class="bg-gradient-to-r from-brand-700 via-indigo-600 to-brand-500 bg-clip-text text-transparent">
                                Postulez avec impact, en quelques clics.
                            </span>
                        </h1>

                        <!-- Subheadline -->
                        <p class="text-sm sm:text-base text-slate-600 leading-relaxed">
                            JobMatch AI centralise les offres d'emploi, calcule la compatibilité réelle avec votre CV grâce aux embeddings vectoriels et prépare vos candidatures sur-mesure.
                        </p>

                        <!-- Key Benefits List -->
                        <div class="space-y-2.5 pt-1 text-xs sm:text-sm text-slate-700">
                            <div class="flex items-center space-x-2.5">
                                <div class="w-5 h-5 rounded-full bg-emerald-100 text-emerald-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span><strong>Scoring Sémantique 0 à 100% :</strong> calcul précis et transparent par intelligence artificielle.</span>
                            </div>
                            <div class="flex items-center space-x-2.5">
                                <div class="w-5 h-5 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span><strong>Lettres et Pitchs IA :</strong> personnalisés par offre avec mots-clés ATS.</span>
                            </div>
                            <div class="flex items-center space-x-2.5">
                                <div class="w-5 h-5 rounded-full bg-brand-100 text-brand-600 flex items-center justify-center shrink-0">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                </div>
                                <span><strong>100% Contrôle Humain :</strong> vous validez chaque étape.</span>
                            </div>
                        </div>

                        <!-- CTA Actions (Full-width on mobile, inline on sm+) -->
                        <div class="pt-2 flex flex-col sm:flex-row items-stretch sm:items-center gap-3">
                            <a href="{{ route('register') }}" class="px-6 py-3.5 rounded-2xl bg-brand-600 hover:bg-brand-700 text-white font-extrabold text-sm sm:text-base shadow-xl shadow-brand-600/30 transition text-center">
                                Créer mon compte candidat &rarr;
                            </a>
                            <a href="{{ route('login') }}" class="px-5 py-3.5 rounded-2xl bg-white hover:bg-slate-50 border border-slate-300 text-slate-800 font-bold text-sm sm:text-base shadow-xs transition text-center">
                                Se connecter au compte démo
                            </a>
                        </div>

                        <p class="text-[11px] sm:text-xs text-slate-400">
                            Inscription instantanée &bull; Sans engagement &bull; 100% de contrôle humain
                        </p>
                    </div>

                    <!-- Right Column: Interactive Video Showcase (Side by Side on same line) -->
                    <div class="lg:col-span-7 w-full">
                        <div class="rounded-2xl sm:rounded-3xl p-1.5 sm:p-2.5 bg-gradient-to-b from-slate-200 via-slate-100 to-slate-200 shadow-2xl border border-slate-300/80">
                            <div class="rounded-xl sm:rounded-2xl bg-slate-900 text-white overflow-hidden shadow-2xl border border-slate-800">
                                
                                <!-- Video Player Header Bar -->
                                <div class="px-4 py-3 bg-slate-950/80 border-b border-slate-800 flex flex-wrap items-center justify-between gap-2">
                            <div class="flex items-center space-x-2">
                                <div class="flex space-x-1.5">
                                    <div class="w-3 h-3 rounded-full bg-rose-500/80"></div>
                                    <div class="w-3 h-3 rounded-full bg-amber-500/80"></div>
                                    <div class="w-3 h-3 rounded-full bg-emerald-500/80"></div>
                                </div>
                                <span class="text-xs font-mono text-slate-400 pl-2 hidden sm:inline">jobmatch-ai-pipeline-demo.mp4</span>
                            </div>

                            <!-- Live Demo Indicator & Play/Pause -->
                            <div class="flex items-center space-x-3">
                                <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] font-bold bg-rose-500/20 text-rose-300 border border-rose-500/30 animate-pulse">
                                    <span class="w-1.5 h-1.5 mr-1 rounded-full bg-rose-500"></span>
                                    DÉMO VIDÉO EN DIRECT
                                </span>
                                <button type="button" id="playPauseBtn" onclick="togglePlayPause()" class="p-1.5 px-2.5 rounded-lg bg-slate-800 hover:bg-slate-700 text-slate-200 text-xs transition flex items-center space-x-1.5" title="Pause / Lecture">
                                    <span id="playPauseIcon" class="flex items-center"><svg class="w-3 h-3" fill="currentColor" viewBox="0 0 24 24"><rect x="6" y="4" width="4" height="16"></rect><rect x="14" y="4" width="4" height="16"></rect></svg></span>
                                    <span id="playPauseText" class="text-[11px] font-semibold hidden sm:inline">Pause</span>
                                </button>
                            </div>
                        </div>

                        <!-- Video Step Navigation Chapters (Tabs) -->
                        <div class="grid grid-cols-2 sm:grid-cols-4 bg-slate-900/90 border-b border-slate-800 text-[11px] sm:text-xs font-semibold">
                            <button type="button" onclick="goToStep(0)" id="tab-0" class="step-tab py-2.5 px-2 text-center border-b-2 border-brand-500 text-white bg-slate-800/60 transition">
                                1. Scan du CV
                            </button>
                            <button type="button" onclick="goToStep(1)" id="tab-1" class="step-tab py-2.5 px-2 text-center border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition">
                                2. Matching 92%
                            </button>
                            <button type="button" onclick="goToStep(2)" id="tab-2" class="step-tab py-2.5 px-2 text-center border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition">
                                3. Rédaction IA
                            </button>
                            <button type="button" onclick="goToStep(3)" id="tab-3" class="step-tab py-2.5 px-2 text-center border-b-2 border-transparent text-slate-400 hover:text-slate-200 transition">
                                4. Envoi Supervisé
                            </button>
                        </div>

                        <!-- Video Stage Screen Container -->
                        <div class="relative min-h-[340px] sm:min-h-[380px] p-4 sm:p-8 flex items-center justify-center bg-radial-gradient from-slate-900 via-slate-950 to-black overflow-hidden">
                            
                            <!-- Ambient glowing background lights -->
                            <div class="absolute -top-24 -left-24 w-72 h-72 bg-brand-600/15 rounded-full blur-3xl pointer-events-none"></div>
                            <div class="absolute -bottom-24 -right-24 w-72 h-72 bg-indigo-600/15 rounded-full blur-3xl pointer-events-none"></div>

                            <!-- STEP 1: CV Scan & Instant Vectorization -->
                            <div id="stage-0" class="stage-frame w-full max-w-2xl space-y-4 text-left transition-all duration-500 opacity-100">
                                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 shadow-2xl relative overflow-hidden backdrop-blur">
                                    <!-- Scanning laser light bar effect -->
                                    <div class="scanner-line absolute left-0 right-0 h-1 bg-gradient-to-r from-transparent via-brand-400 to-transparent shadow-[0_0_15px_#38bdf8] animate-scan pointer-events-none"></div>
                                    
                                    <div class="flex items-center justify-between pb-3 border-b border-slate-700">
                                        <div class="flex items-center space-x-3">
                                            <div class="w-10 h-10 rounded-xl bg-brand-500/20 text-brand-400 flex items-center justify-center font-bold border border-brand-500/30">
                                                <svg class="w-5 h-5 text-brand-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                                            </div>
                                            <div>
                                                <h4 class="text-sm sm:text-base font-bold text-white">CV_Candidat_2026.pdf</h4>
                                                <p class="text-xs text-brand-300 font-mono">Scan vectoriel IA en cours...</p>
                                            </div>
                                        </div>
                                        <span class="px-2.5 py-1 rounded-full text-[11px] font-bold bg-brand-500/20 text-brand-300 border border-brand-500/40">
                                            Scan sémantique IA
                                        </span>
                                    </div>

                                    <div class="mt-4 space-y-2">
                                        <p class="text-xs text-slate-400 uppercase tracking-wider font-semibold">Compétences extraites instantanément :</p>
                                        <div class="flex flex-wrap gap-1.5">
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">Architecture Logicielle</span>
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">Conception Web & Cloud</span>
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">Méthodologies Agiles</span>
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">CI/CD & DevOps</span>
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">Gestion de Données</span>
                                            <span class="px-2.5 py-1 rounded-lg text-xs font-bold bg-emerald-500/20 text-emerald-300 border border-emerald-500/40">APIs & Web Services</span>
                                        </div>
                                    </div>

                                    <div class="mt-4 p-2.5 rounded-xl bg-slate-900/60 border border-slate-700/60 flex items-center justify-between text-xs">
                                        <span class="text-slate-300">Expérience : ~4 ans &bull; Master Informatique</span>
                                        <span class="text-emerald-400 font-bold">100% Structuré</span>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 2: Semantic Matching 92.5% -->
                            <div id="stage-1" class="stage-frame w-full max-w-2xl space-y-4 text-left transition-all duration-500 opacity-0 hidden">
                                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 shadow-2xl backdrop-blur space-y-4">
                                    <div class="flex items-center justify-between">
                                        <span class="text-xs font-mono text-brand-400">Algorithme de Similarité &bull; Appariement Sémantique</span>
                                        <span class="px-3 py-1 rounded-full text-xs font-black bg-emerald-500/20 text-emerald-300 border border-emerald-500/40 shadow-[0_0_15px_rgba(16,185,129,0.3)]">
                                            Score Calculé : 92.5%
                                        </span>
                                    </div>

                                    <div class="p-3.5 bg-slate-900/70 rounded-xl border border-slate-700/80">
                                        <h4 class="text-base font-black text-white">Ingénieur d'Études & Conception Logicielle</h4>
                                        <p class="text-xs font-semibold text-brand-400 mt-0.5">InnoTech Solutions &bull; Paris / Télétravail</p>
                                        <p class="text-xs text-slate-300 mt-2 leading-relaxed">
                                            « Vous participerez activement au développement et à l'évolution de nos plateformes numériques de pointe... »
                                        </p>
                                    </div>

                                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-2 text-xs">
                                        <div class="p-2.5 rounded-xl bg-emerald-950/40 border border-emerald-500/30 text-emerald-300">
                                            <span class="font-bold block">5 compétences clés alignées</span>
                                            <span class="text-[11px] text-emerald-400">Architecture Logicielle, APIs, Agilité, Données, DevOps</span>
                                        </div>
                                        <div class="p-2.5 rounded-xl bg-blue-950/40 border border-blue-500/30 text-blue-300">
                                            <span class="font-bold block">Filtres durs validés</span>
                                            <span class="text-[11px] text-blue-400">Contrat CDI désiré &bull; Salaire compatible</span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 3: Real-Time AI Generation -->
                            <div id="stage-2" class="stage-frame w-full max-w-2xl space-y-4 text-left transition-all duration-500 opacity-0 hidden">
                                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 shadow-2xl backdrop-blur space-y-3">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center space-x-2">
                                            <span class="w-2 h-2 rounded-full bg-indigo-400 animate-ping"></span>
                                            <span class="text-xs font-bold text-indigo-300 font-mono">Modèle de Langage IA &bull; Rédaction Personnalisée</span>
                                        </div>
                                        <span class="text-[11px] font-mono text-slate-400">ATS Optimization : On</span>
                                    </div>

                                    <!-- Typing simulator text -->
                                    <div class="p-4 bg-slate-950/90 rounded-xl border border-slate-800 font-mono text-xs sm:text-[13px] text-slate-200 leading-relaxed min-h-[120px]">
                                        <span id="typedLetterText" class="text-slate-100"></span>
                                        <span class="inline-block w-2 h-4 bg-brand-400 animate-pulse ml-0.5"></span>
                                    </div>

                                    <div class="p-2.5 rounded-xl bg-indigo-950/50 border border-indigo-500/30 text-xs text-indigo-200 flex items-center justify-between">
                                        <span><strong class="text-white">Recommandation ATS :</strong> Mettre en avant vos réussites en conception d'architectures</span>
                                        <span class="font-bold text-indigo-400 shrink-0">Inclus</span>
                                    </div>
                                </div>
                            </div>

                            <!-- STEP 4: Human Validation & Kanban -->
                            <div id="stage-3" class="stage-frame w-full max-w-2xl space-y-4 text-left transition-all duration-500 opacity-0 hidden">
                                <div class="bg-slate-800/80 border border-slate-700/80 rounded-2xl p-5 shadow-2xl backdrop-blur space-y-4 text-center">
                                    <div class="w-12 h-12 rounded-2xl bg-emerald-500/20 text-emerald-400 flex items-center justify-center mx-auto border border-emerald-500/30 shadow-[0_0_20px_rgba(16,185,129,0.3)]">
                                        <svg class="w-6 h-6 text-emerald-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                                    </div>
                                    <div>
                                        <h4 class="text-base font-black text-white">Candidature Revalidée & Envoyée !</h4>
                                        <p class="text-xs text-slate-400 mt-1 max-w-md mx-auto">
                                            L'utilisateur a relu la lettre, validé l'email en 1 clic et déplacé l'offre dans son suivi.
                                        </p>
                                    </div>

                                    <!-- Simulated Mini Kanban -->
                                    <div class="grid grid-cols-3 gap-2 text-left pt-2">
                                        <div class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-800">
                                            <span class="text-[10px] uppercase font-bold text-slate-500">1. Brouillon</span>
                                            <p class="text-xs text-slate-400 mt-1 line-through">InnoTech Solutions</p>
                                        </div>
                                        <div class="p-2.5 rounded-xl bg-slate-900/60 border border-slate-800">
                                            <span class="text-[10px] uppercase font-bold text-blue-400">2. Validée</span>
                                            <p class="text-xs text-slate-400 mt-1 line-through">InnoTech Solutions</p>
                                        </div>
                                        <div class="p-2.5 rounded-xl bg-emerald-950/50 border border-emerald-500/40 shadow-sm animate-pulse">
                                            <span class="text-[10px] uppercase font-bold text-emerald-400">3. Entretien</span>
                                            <p class="text-xs font-bold text-white mt-1">InnoTech Solutions</p>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        </div>

                        <!-- Video Player Bottom Controls & Progress Bar -->
                        <div class="px-4 py-3 bg-slate-950 border-t border-slate-800 flex flex-col sm:flex-row items-center justify-between gap-2 text-xs text-slate-400">
                            <!-- Progress Bar -->
                            <div class="w-full flex-1 sm:mr-4">
                                <div class="w-full h-1.5 bg-slate-800 rounded-full overflow-hidden cursor-pointer" onclick="onProgressBarClick(event)" id="progressBarContainer">
                                    <div id="videoProgressBar" class="h-full bg-gradient-to-r from-brand-500 via-indigo-500 to-emerald-400 w-1/4 transition-all duration-300"></div>
                                </div>
                            </div>

                            <div class="flex items-center justify-between w-full sm:w-auto space-x-3 text-[11px] font-mono">
                                <span id="timeCounter">00:04 / 00:16</span>
                                <button type="button" onclick="restartDemo()" class="hover:text-white transition flex items-center space-x-1">
                                    <span>↻ Rejouer</span>
                                </button>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

        </div>

                <style>
                    @keyframes scan {
                        0% { top: 0%; opacity: 0.8; }
                        50% { top: 90%; opacity: 1; }
                        100% { top: 0%; opacity: 0.8; }
                    }
                    .animate-scan {
                        animation: scan 2.5s ease-in-out infinite;
                    }
                </style>

                <script>
                    let currentStep = 0;
                    const totalSteps = 4;
                    const stepDuration = 4200; // 4.2 seconds per step
                    let timer = null;
                    let isPlaying = true;
                    let typingTimer = null;

                    const letterSample = "« Madame, Monsieur,\nC'est avec un vif intérêt que je postule à votre offre d'Ingénieur d'Études & Conception Logicielle chez InnoTech Solutions. Fort de 4 années d'expérience dans la conception de solutions performantes et scalables, je souhaite mettre mes compétences au service de vos projets... »";

                    function showStep(index) {
                        currentStep = index;
                        for (let i = 0; i < totalSteps; i++) {
                            const stage = document.getElementById(`stage-${i}`);
                            const tab = document.getElementById(`tab-${i}`);
                            if (i === index) {
                                stage.classList.remove('hidden', 'opacity-0');
                                stage.classList.add('opacity-100');
                                tab.classList.add('border-brand-500', 'text-white', 'bg-slate-800/60');
                                tab.classList.remove('border-transparent', 'text-slate-400');
                            } else {
                                stage.classList.add('hidden', 'opacity-0');
                                stage.classList.remove('opacity-100');
                                tab.classList.remove('border-brand-500', 'text-white', 'bg-slate-800/60');
                                tab.classList.add('border-transparent', 'text-slate-400');
                            }
                        }

                        // Progress bar update
                        const pct = ((index + 1) / totalSteps) * 100;
                        document.getElementById('videoProgressBar').style.width = `${pct}%`;
                        const seconds = (index + 1) * 4;
                        document.getElementById('timeCounter').innerText = `00:${seconds < 10 ? '0' + seconds : seconds} / 00:16`;

                        // Typing effect on step 2 (0-indexed 2 is Step 3)
                        if (index === 2) {
                            startTypingEffect();
                        }
                    }

                    function startTypingEffect() {
                        clearInterval(typingTimer);
                        const target = document.getElementById('typedLetterText');
                        target.innerText = "";
                        let charIndex = 0;
                        typingTimer = setInterval(() => {
                            if (charIndex < letterSample.length) {
                                target.innerText += letterSample[charIndex];
                                charIndex++;
                            } else {
                                clearInterval(typingTimer);
                            }
                        }, 22);
                    }

                    function nextStep() {
                        currentStep = (currentStep + 1) % totalSteps;
                        showStep(currentStep);
                    }

                    function startLoop() {
                        clearInterval(timer);
                        timer = setInterval(() => {
                            if (isPlaying) {
                                nextStep();
                            }
                        }, stepDuration);
                    }

                    function goToStep(index) {
                        showStep(index);
                        startLoop();
                    }

                    function togglePlayPause() {
                        isPlaying = !isPlaying;
                        const icon = document.getElementById('playPauseIcon');
                        const text = document.getElementById('playPauseText');
                        if (isPlaying) {
                            icon.innerText = '⏸️';
                            text.innerText = 'Pause';
                            startLoop();
                        } else {
                            icon.innerText = '▶️';
                            text.innerText = 'Lecture';
                            clearInterval(timer);
                        }
                    }

                    function restartDemo() {
                        showStep(0);
                        isPlaying = true;
                        document.getElementById('playPauseIcon').innerText = '⏸️';
                        document.getElementById('playPauseText').innerText = 'Pause';
                        startLoop();
                    }

                    function onProgressBarClick(e) {
                        const rect = document.getElementById('progressBarContainer').getBoundingClientRect();
                        const clickX = e.clientX - rect.left;
                        const ratio = clickX / rect.width;
                        const step = Math.min(totalSteps - 1, Math.floor(ratio * totalSteps));
                        goToStep(step);
                    }

                    // Auto-start on load
                    document.addEventListener('DOMContentLoaded', () => {
                        showStep(0);
                        startLoop();
                    });
                </script>

            </div>
        </section>

        <!-- Features Grid -->
        <section class="py-14 sm:py-20 bg-white border-t border-slate-200">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center max-w-3xl mx-auto mb-10 sm:mb-16">
                    <h2 class="text-xs font-bold text-brand-600 uppercase tracking-widest">Une Suite Conçue pour Décrocher des Entretiens</h2>
                    <p class="text-2xl sm:text-3xl font-black text-slate-950 mt-2 tracking-tight">Comment JobMatch AI fait la différence</p>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 sm:gap-6">
                    <!-- Feature 1 -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:shadow-md transition">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-blue-100 text-blue-700 flex items-center justify-center mb-3 sm:mb-4">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Veille Multi-sources</h3>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            Agrégation intelligente d'APIs d'emploi officielles (France Travail, Adzuna) et flux RSS avec dédoublonnage automatique.
                        </p>
                    </div>

                    <!-- Feature 2 -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:shadow-md transition">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-emerald-100 text-emerald-700 flex items-center justify-center mb-3 sm:mb-4">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path></svg>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Matching Vectoriel IA</h3>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            Calcul de similarité sémantique avancée. Vos compétences réelles sont comprises au-delà des simples mots-clés.
                        </p>
                    </div>

                    <!-- Feature 3 -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:shadow-md transition">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-indigo-100 text-indigo-700 flex items-center justify-center mb-3 sm:mb-4">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Candidature Personnalisée</h3>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            Génération de lettres percutantes adaptées à chaque annonce, avec conseils concrets pour optimiser votre CV pour les ATS.
                        </p>
                    </div>

                    <!-- Feature 4 -->
                    <div class="p-5 sm:p-6 rounded-2xl bg-slate-50 border border-slate-200 hover:shadow-md transition">
                        <div class="w-10 h-10 sm:w-12 sm:h-12 rounded-xl bg-amber-100 text-amber-700 flex items-center justify-center mb-3 sm:mb-4">
                            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                        <h3 class="text-sm sm:text-base font-bold text-slate-900">Human-in-the-Loop</h3>
                        <p class="text-xs text-slate-600 mt-2 leading-relaxed">
                            Zéro envoi non maîtrisé. Vous restez maître du contenu, l'ajustez à volonté et décidez de chaque candidature.
                        </p>
                    </div>
                </div>
            </div>
        </section>

        <!-- CTA Banner -->
        <section class="py-12 sm:py-16 bg-gradient-to-r from-brand-900 via-indigo-900 to-slate-900 text-white text-center">
            <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 space-y-4 sm:space-y-6">
                <h2 class="text-2xl sm:text-4xl font-black tracking-tight leading-snug">Prêt à transformer votre recherche d'opportunités ?</h2>
                <p class="text-brand-200 text-xs sm:text-base max-w-xl mx-auto px-2">
                    Rejoignez JobMatch AI dès aujourd'hui et activez votre assistant personnel de candidature.
                </p>
                <div class="pt-2">
                    <a href="{{ route('register') }}" class="w-full sm:w-auto inline-flex justify-center px-8 py-3.5 sm:py-4 rounded-2xl bg-white text-brand-900 font-extrabold text-sm shadow-lg hover:bg-brand-50 transition">
                        Commencer Maintenant &rarr;
                    </a>
                </div>
            </div>
        </section>

    </main>

    <!-- Footer (Responsive) -->
    <footer class="bg-white border-t border-slate-200 py-6 sm:py-8">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center text-[11px] sm:text-xs text-slate-500 gap-3 text-center sm:text-left">
            <div>
                &copy; 2026 JobMatch AI. Tous droits réservés.
            </div>
            <div class="flex items-center space-x-4 sm:space-x-6 text-slate-500">
                <a href="{{ route('login') }}" class="hover:text-slate-900">Connexion</a>
                <a href="{{ route('register') }}" class="hover:text-slate-900">Inscription</a>
                <span>Plateforme d'Aide à la Candidature & Matching d'Opportunités</span>
            </div>
        </div>
    </footer>

</body>
</html>
