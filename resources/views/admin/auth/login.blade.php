<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>Sign in &bull; {{ config('admin.title') }}</title>
    <meta name="robots" content="noindex, nofollow">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Caveat:wght@400..700&family=Newsreader:ital,opsz,wght@0,6..72,400..700;1,6..72,400..700&family=Plus+Jakarta+Sans:ital,wght@0,300..800;1,300..800&display=swap" rel="stylesheet">

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body class="flex min-h-full flex-col items-center justify-center bg-brand-dark px-4 py-12">

    {{-- Decorative brand wash --}}
    <div class="pointer-events-none fixed inset-0 overflow-hidden" aria-hidden="true">
        <div class="absolute -top-32 -left-24 h-72 w-72 rounded-full bg-brand-accent/25 blur-3xl"></div>
        <div class="absolute -right-24 -bottom-32 h-80 w-80 rounded-full bg-teal-500/15 blur-3xl"></div>
    </div>

    <div class="relative w-full max-w-md animate-rise">
        {{-- Brand --}}
        <div class="mb-8 flex flex-col items-center text-center">
            <img src="/images/logo-light.png" alt=""
                 class="mb-4 h-14 w-14 object-contain">
            <h1 class="font-editorial text-3xl font-bold text-white">{{ config('clinic.name') }}</h1>
            <p class="mt-1 text-xs font-semibold tracking-[0.22em] text-teal-200 uppercase">{{ config('admin.title') }}</p>
        </div>

        <div class="rounded-2xl border border-white/10 bg-white p-7 shadow-2xl sm:p-8">
            <h2 class="font-editorial text-2xl font-bold text-brand-teal">Staff sign in</h2>
            <p class="mt-1 text-xs text-slate-500">Sign in to review booking requests and manage the website.</p>

            @if ($errors->any())
                <div class="mt-5 flex items-start gap-2.5 rounded-lg border border-red-200 bg-red-50 p-3 text-xs text-red-800">
                    <svg class="mt-0.5 h-4 w-4 flex-shrink-0 text-red-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true">
                        <circle cx="12" cy="12" r="10" />
                        <path d="M12 8v4M12 16h.01" />
                    </svg>
                    <p>{{ $errors->first() }}</p>
                </div>
            @endif

            <form method="POST" action="{{ route('admin.login.store') }}" class="mt-6 space-y-4">
                @csrf

                <div>
                    <label for="login-email" class="mb-1 block text-xs font-semibold text-slate-700">Email address</label>
                    <input id="login-email" type="email" name="email" required autofocus autocomplete="username"
                           value="{{ old('email') }}"
                           class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none @error('email') border-red-400 @enderror"
                           placeholder="you@clinic.com.au">
                </div>

                <div>
                    <label for="login-password" class="mb-1 block text-xs font-semibold text-slate-700">Password</label>
                    <input id="login-password" type="password" name="password" required autocomplete="current-password"
                           class="w-full rounded-lg border border-slate-300 px-3.5 py-2.5 text-sm text-slate-800 transition focus:border-transparent focus:ring-2 focus:ring-brand-teal focus:outline-none @error('password') border-red-400 @enderror"
                           placeholder="••••••••">
                </div>

                <label class="flex items-center gap-2 text-xs text-slate-600">
                    <input type="checkbox" name="remember" value="1"
                           class="rounded border-slate-300 text-brand-teal focus:ring-brand-teal">
                    <span>Keep me signed in</span>
                </label>

                <button type="submit"
                        class="w-full rounded-lg bg-brand-teal py-2.5 text-sm font-semibold text-white shadow-sm transition-all hover:bg-brand-dark active:scale-[0.99]">
                    Sign in
                </button>
            </form>
        </div>

        <p class="mt-6 text-center text-xs text-slate-400">
            <a href="{{ route('home') }}" class="transition-colors hover:text-white">&larr; Back to the clinic website</a>
        </p>
    </div>
</body>
</html>
