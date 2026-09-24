<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Connexion — JobMatch AI</title>
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
<body class="min-h-full flex flex-col justify-center py-10 sm:py-16 px-4 sm:px-6 lg:px-8 font-sans antialiased bg-slate-50 selection:bg-brand-500 selection:text-white">

    <div class="sm:mx-auto sm:w-full sm:max-w-md text-center">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="inline-flex items-center space-x-2.5 mb-2 group">
            <div class="w-11 h-11 rounded-2xl bg-gradient-to-tr from-brand-900 via-brand-700 to-indigo-600 flex items-center justify-center text-white font-black text-xl shadow-lg shadow-brand-500/25 group-hover:scale-105 transition">
                JM
            </div>
            <div class="text-left">
                <span class="text-xl font-black tracking-tight text-slate-900">JobMatch <span class="text-brand-600">AI</span></span>
                <span class="block text-[9px] text-slate-400 uppercase tracking-widest font-bold">Plateforme Recrutement & IA</span>
            </div>
        </a>
        <h2 class="mt-4 text-2xl font-black tracking-tight text-slate-950">
            Connexion à votre espace
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Accédez à vos opportunités d'emploi et vos candidatures personnalisées
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-5 sm:px-10 shadow-xl shadow-slate-200/50 rounded-2xl sm:rounded-3xl border border-slate-200">

            <!-- 1-Click Login Helper -->
            @php
                $configuredEmail = env('DEFAULT_USER_EMAIL', env('ADMIN_EMAIL', 'candidat.demo@jobmatch.ai'));
                $configuredPassword = env('DEFAULT_USER_PASSWORD', env('ADMIN_PASSWORD', 'password123'));
                $isCustomUser = !empty(env('DEFAULT_USER_EMAIL')) || !empty(env('ADMIN_EMAIL'));
            @endphp
            <div class="mb-5 p-3.5 sm:p-4 rounded-2xl bg-gradient-to-r from-brand-50 to-indigo-50 border border-brand-200/80 shadow-xs">
                <div class="flex items-center justify-between gap-2">
                    <div>
                        <span class="inline-flex items-center text-[10px] font-extrabold text-brand-800 uppercase tracking-wider">
                            {{ $isCustomUser ? 'Mon Compte' : 'Compte Démo' }}
                        </span>
                        <p class="text-xs text-brand-900 font-bold mt-0.5">
                            {{ $configuredEmail }}
                            <span class="text-slate-500 font-normal block sm:inline">(MDP : {{ $configuredPassword }})</span>
                        </p>
                    </div>
                    <button type="button" onclick="fillDemoCredentials()" class="px-3 py-1.5 rounded-xl bg-brand-600 hover:bg-brand-700 text-white text-xs font-bold shadow-sm transition shrink-0">
                        Remplir en 1 clic
                    </button>
                </div>
            </div>

            <!-- Error Alerts -->
            @if($errors->any())
                <div class="mb-5 p-3.5 rounded-2xl bg-rose-50 border border-rose-200 text-xs text-rose-800">
                    <ul class="list-disc pl-4 space-y-0.5">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Login Form -->
            <form action="{{ route('login') }}" method="POST" id="loginForm" class="space-y-4">
                @csrf

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Adresse Email
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" placeholder="nom@exemple.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:outline-none transition">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Mot de passe
                    </label>
                    <input id="password" name="password" type="password" autocomplete="current-password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:outline-none transition">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center justify-between pt-0.5">
                    <div class="flex items-center">
                        <input id="remember" name="remember" type="checkbox" class="h-4 w-4 rounded border-slate-300 text-brand-600 focus:ring-brand-500">
                        <label for="remember" class="ml-2 block text-xs text-slate-600 font-medium cursor-pointer">
                            Se souvenir de moi
                        </label>
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full flex items-center justify-center py-3.5 px-4 rounded-xl shadow-lg shadow-brand-600/25 text-sm font-extrabold text-white bg-brand-600 hover:bg-brand-700 focus:outline-none transition">
                        <span>Se connecter &rarr;</span>
                    </button>
                </div>
            </form>

            <!-- Switch to Register & Home -->
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-xs">
                <p class="text-slate-500">
                    Pas encore de compte ?
                    <a href="{{ route('register') }}" class="font-bold text-brand-600 hover:text-brand-700 hover:underline">
                        Créer un compte
                    </a>
                </p>
                <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-600 font-medium">
                    &larr; Accueil
                </a>
            </div>

        </div>
    </div>

    <script>
        function fillDemoCredentials() {
            document.getElementById('email').value = "{{ $configuredEmail }}";
            document.getElementById('password').value = "{{ $configuredPassword }}";
        }
    </script>
</body>
</html>
