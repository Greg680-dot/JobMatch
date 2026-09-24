<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>@yield('title', 'JobMatch AI — Opportunités & Candidatures Intelligentes')</title>
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
<body class="min-h-full flex flex-col font-sans text-slate-800 antialiased selection:bg-brand-500 selection:text-white pb-16 md:pb-0">

    <!-- Top Navigation Bar -->
    <header class="bg-white/90 backdrop-blur-md border-b border-slate-200 sticky top-0 z-40">
        <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8">
            <div class="flex justify-between h-14 sm:h-16 items-center">
                
                <!-- Logo & Brand -->
                <div class="flex items-center space-x-2 sm:space-x-3">
                    <a href="{{ auth()->check() ? route('dashboard') : route('home') }}" class="flex items-center space-x-2">
                        <div class="w-8 h-8 sm:w-10 sm:h-10 rounded-xl bg-gradient-to-tr from-brand-900 via-brand-700 to-indigo-600 flex items-center justify-center text-white font-black text-sm sm:text-lg shadow-md shadow-brand-500/20 shrink-0">
                            JM
                        </div>
                        <div>
                            <span class="text-base sm:text-xl font-black tracking-tight text-brand-900">JobMatch <span class="text-brand-600">AI</span></span>
                            <span class="hidden sm:block text-[9px] text-slate-400 uppercase tracking-widest font-semibold">Candidature Assistée</span>
                        </div>
                    </a>
                </div>

                <!-- Desktop Navigation Links -->
                @auth
                    <nav class="hidden md:flex items-center space-x-1 lg:space-x-2">
                        <a href="{{ route('dashboard') }}" class="px-3 py-2 rounded-xl text-xs lg:text-sm font-bold {{ request()->routeIs('dashboard') ? 'text-brand-700 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                            Opportunités
                        </a>
                        <a href="{{ route('candidatures.index') }}" class="px-3 py-2 rounded-xl text-xs lg:text-sm font-bold {{ request()->routeIs('candidatures.*') ? 'text-brand-700 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                            Mes Candidatures
                        </a>
                        <a href="{{ route('profile.show') }}" class="px-3 py-2 rounded-xl text-xs lg:text-sm font-bold {{ request()->routeIs('profile.show') ? 'text-brand-700 bg-brand-50' : 'text-slate-600 hover:text-slate-900 hover:bg-slate-100' }} transition">
                            Mon Profil & CV
                        </a>
                    </nav>
                @endauth

                    <!-- Status Badge (IA Active Vert Émeraude) -->
                    <div class="flex items-center px-2.5 py-1 rounded-full text-xs font-semibold bg-emerald-50 text-emerald-700 border border-emerald-200">
                        <span class="w-2 h-2 mr-1.5 rounded-full bg-emerald-500 animate-pulse"></span>
                        <span>IA Active</span>
                    </div>

                    @auth
                        <div class="flex items-center space-x-2 pl-2 border-l border-slate-200">
                            <div class="w-8 h-8 rounded-full bg-brand-100 text-brand-700 font-bold flex items-center justify-center text-xs shrink-0" title="{{ auth()->user()->name }}">
                                {{ strtoupper(substr(auth()->user()->name, 0, 2)) }}
                            </div>
                            <span class="text-xs font-bold text-slate-800 hidden sm:inline max-w-[120px] truncate">{{ auth()->user()->name }}</span>
                            
                            <form action="{{ route('logout') }}" method="POST" class="inline">
                                @csrf
                                <button type="submit" class="p-1.5 sm:px-2 sm:py-1 rounded-lg text-slate-400 hover:text-rose-600 hover:bg-rose-50 text-xs font-medium transition" title="Se déconnecter">
                                    <span class="hidden sm:inline">Déconnexion</span>
                                    <svg class="w-4 h-4 sm:hidden" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                </button>
                            </form>
                        </div>
                    @else
                        <div class="flex items-center space-x-2">
                            <a href="{{ route('login') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold text-slate-700 hover:bg-slate-100 transition">Connexion</a>
                            <a href="{{ route('register') }}" class="px-3 py-1.5 rounded-xl text-xs font-bold text-white bg-brand-600 hover:bg-brand-700 shadow-xs transition">Inscription</a>
                        </div>
                    @endauth
                </div>
            </div>
        </div>
    </header>

    <!-- Flash Notifications (Mobile-friendly) -->
    <div class="max-w-7xl mx-auto px-3 sm:px-6 lg:px-8 mt-3 sm:mt-4 w-full">
        @if(session('success'))
            <div class="p-3 sm:p-4 mb-3 text-xs sm:text-sm text-emerald-800 rounded-xl bg-emerald-50 border border-emerald-200 flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-emerald-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('success') }}</span>
                </div>
            </div>
        @endif

        @if(session('error'))
            <div class="p-3 sm:p-4 mb-3 text-xs sm:text-sm text-rose-800 rounded-xl bg-rose-50 border border-rose-200 flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-rose-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('error') }}</span>
                </div>
            </div>
        @endif
        
        @if(session('warning'))
            <div class="p-3 sm:p-4 mb-3 text-xs sm:text-sm text-amber-800 rounded-xl bg-amber-50 border border-amber-200 flex items-center justify-between shadow-xs">
                <div class="flex items-center space-x-2">
                    <svg class="w-4 h-4 sm:w-5 sm:h-5 text-amber-600 shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path></svg>
                    <span>{{ session('warning') }}</span>
                </div>
            </div>
        @endif
    </div>

    <!-- Main Content Area -->
    <main class="flex-1 max-w-7xl w-full mx-auto px-3 sm:px-6 lg:px-8 py-4 sm:py-6">
        @yield('content')
    </main>

    <!-- Mobile App Bottom Navigation Bar (Visible only on mobile screens < md) -->
    @auth
        <nav class="md:hidden fixed bottom-0 left-0 right-0 z-40 bg-white/95 backdrop-blur-lg border-t border-slate-200 py-2 px-4 flex justify-around items-center shadow-lg">
            <a href="{{ route('dashboard') }}" class="flex flex-col items-center py-1 px-3 rounded-xl {{ request()->routeIs('dashboard') ? 'text-brand-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }} transition">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 13.255A23.931 23.931 0 0112 15c-3.183 0-6.22-.62-9-1.745M16 6V4a2 2 0 00-2-2h-4a2 2 0 00-2 2v2m4 6h.01M5 20h14a2 2 0 002-2V8a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path></svg>
                <span class="text-[10px] tracking-tight">Opportunités</span>
            </a>
            <a href="{{ route('candidatures.index') }}" class="flex flex-col items-center py-1 px-3 rounded-xl {{ request()->routeIs('candidatures.*') ? 'text-brand-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }} transition">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                <span class="text-[10px] tracking-tight">Candidatures</span>
            </a>
            <a href="{{ route('profile.show') }}" class="flex flex-col items-center py-1 px-3 rounded-xl {{ request()->routeIs('profile.show') ? 'text-brand-600 font-bold' : 'text-slate-500 hover:text-slate-800 font-medium' }} transition">
                <svg class="w-5 h-5 mb-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                <span class="text-[10px] tracking-tight">Mon Profil</span>
            </a>
        </nav>
    @endauth

    <!-- Footer -->
    <footer class="bg-white border-t border-slate-200 mt-auto py-5 sm:py-6">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 flex flex-col sm:flex-row justify-between items-center text-[11px] sm:text-xs text-slate-500 gap-2 sm:gap-0 text-center sm:text-left">
            <div>
                &copy; 2026 JobMatch AI. Tous droits réservés.
            </div>
            <div class="flex items-center space-x-3 text-slate-400">
                <span>Plateforme d'Aide à la Candidature & Matching d'Opportunités</span>
            </div>
        </div>
    </footer>

</body>
</html>
