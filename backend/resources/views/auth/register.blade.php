<!DOCTYPE html>
<html lang="fr" class="h-full bg-slate-50">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <title>Inscription — JobMatch AI</title>
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
            Créer votre compte candidat
        </h2>
        <p class="mt-1 text-xs text-slate-500">
            Rejoignez JobMatch AI et activez votre assistant de recherche
        </p>
    </div>

    <div class="mt-6 sm:mx-auto sm:w-full sm:max-w-md">
        <div class="bg-white py-8 px-5 sm:px-10 shadow-xl shadow-slate-200/50 rounded-2xl sm:rounded-3xl border border-slate-200">

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

            <!-- Register Form -->
            <form action="{{ route('register') }}" method="POST" class="space-y-3.5 sm:space-y-4">
                @csrf

                <!-- Nom Complet -->
                <div>
                    <label for="name" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Nom complet
                    </label>
                    <input id="name" name="name" type="text" autocomplete="name" required value="{{ old('name') }}" placeholder="Ex: Thomas Dupont" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:outline-none transition">
                </div>

                <!-- Email -->
                <div>
                    <label for="email" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Adresse Email
                    </label>
                    <input id="email" name="email" type="email" autocomplete="email" required value="{{ old('email') }}" placeholder="votre.nom@exemple.com" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:outline-none transition">
                </div>

                <!-- Password -->
                <div>
                    <label for="password" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Mot de passe (8 car. min)
                    </label>
                    <input id="password" name="password" type="password" autocomplete="new-password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:outline-none transition">
                </div>

                <!-- Password Confirmation -->
                <div>
                    <label for="password_confirmation" class="block text-xs font-bold text-slate-700 uppercase tracking-wider mb-1.5">
                        Confirmer le mot de passe
                    </label>
                    <input id="password_confirmation" name="password_confirmation" type="password" autocomplete="new-password" required placeholder="••••••••" class="w-full px-3.5 py-2.5 rounded-xl border border-slate-300 text-sm focus:ring-2 focus:ring-brand-500 focus:border-brand-500 focus:outline-none transition">
                </div>

                <!-- Terms -->
                <p class="text-[11px] text-slate-400 leading-relaxed pt-1">
                    En vous inscrivant, vous acceptez le traitement sécurisé de vos données pour le matching d'opportunités conformément au RGPD.
                </p>

                <!-- Submit Button -->
                <div class="pt-2">
                    <button type="submit" class="w-full flex items-center justify-center py-3.5 px-4 rounded-xl shadow-lg shadow-brand-600/25 text-sm font-extrabold text-white bg-brand-600 hover:bg-brand-700 focus:outline-none transition">
                        <span>Créer mon compte &rarr;</span>
                    </button>
                </div>
            </form>

            <!-- Switch to Login & Home -->
            <div class="mt-6 pt-5 border-t border-slate-100 flex items-center justify-between text-xs">
                <p class="text-slate-500">
                    Déjà un compte ?
                    <a href="{{ route('login') }}" class="font-bold text-brand-600 hover:text-brand-700 hover:underline">
                        Se connecter
                    </a>
                </p>
                <a href="{{ route('home') }}" class="text-slate-400 hover:text-slate-600 font-medium">
                    &larr; Accueil
                </a>
            </div>

        </div>
    </div>
</body>
</html>
