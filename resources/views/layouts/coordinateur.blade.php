<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Gestion de Présences - Coordinateur')</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.css" rel="stylesheet" />
    <script src="https://cdnjs.cloudflare.com/ajax/libs/flowbite/2.3.0/flowbite.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    @yield('head')
</head>

<body class="bg-gray-50 min-h-screen text-gray-900">
    <div class="flex min-h-screen">
        {{-- Sidebar --}}
        <aside class="w-64 bg-white border-r flex flex-col justify-between">
            <div>
                <div class="p-6 flex items-center gap-2">
                    <svg class="w-7 h-7 text-violet-600" fill="none" stroke="currentColor" stroke-width="2"
                        viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2"></path>
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="2"
                            fill="none" />
                    </svg>
                    <span class="text-lg font-bold text-violet-700">Gestion de Présences - Coordinateur</span>
                </div>
                <nav class="mt-8">
                    <ul>
                        <li>
                            <a href="{{ route('coordinateur.index') }}"
                                class="flex items-center px-6 py-3 font-semibold rounded-r-full mb-2
                                {{ request()->routeIs('coordinateur.index') ? 'text-violet-700 bg-violet-100' : 'text-gray-700 hover:bg-violet-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M3 12l2-2m0 0l7-7 7 7m-9 2v8m4-8v8m5 0h2a2 2 0 002-2v-7a2 2 0 00-.59-1.41l-8-8a2 2 0 00-2.83 0l-8 8A2 2 0 002 13v7a2 2 0 002 2h2">
                                    </path>
                                </svg>
                                Tableau de Bord
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('coordinateur.seances') }}"
                                class="flex items-center px-6 py-3 font-semibold rounded-r-full mb-2
                                {{ request()->routeIs('coordinateur.seances') ? 'text-violet-700 bg-violet-100' : 'text-gray-700 hover:bg-violet-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                                Séances
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('coordinateur.emploi') }}"
                                class="flex items-center px-6 py-3 font-semibold rounded-r-full mb-2
                                {{ request()->routeIs('coordinateur.emploi') ? 'text-violet-700 bg-violet-100' : 'text-gray-700 hover:bg-violet-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 3h18v18H3V3z"></path>
                                </svg>
                                Emploi du Temps
                            </a>
                        </li>
                        <li>
                            <a href="{{ route('coordinateur.absences') }}"
                                class="flex items-center px-6 py-3 font-semibold rounded-r-full mb-2
                                {{ request()->routeIs('coordinateur.absences') ? 'text-violet-700 bg-violet-100' : 'text-gray-700 hover:bg-violet-50' }}">
                                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" stroke-width="2"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round"
                                        d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z">
                                    </path>
                                </svg>
                                Absences
                            </a>
                        </li>
                    </ul>
                </nav>
            </div>
            @if (auth()->check())
                <div class="p-6 flex items-center gap-3 border-t bg-violet-50">
                    <img src="https://i.pravatar.cc/40?img={{ auth()->user()->id }}" alt="{{ auth()->user()->nom }}"
                        class="w-10 h-10 rounded-full border-2 border-violet-300">
                    <span class="font-medium">
                        {{ auth()->user()->nom }} {{ auth()->user()->prenom }}
                        <span
                            class="ml-2 px-2 py-0.5 text-xs rounded bg-violet-200 text-violet-800 font-semibold">Coordinateur</span>
                    </span>
                    <a href="{{ route('logout') }}" class="ml-auto text-violet-700 hover:text-violet-800 transition"
                        onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1">
                            </path>
                        </svg>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" class="hidden">
                        @csrf
                    </form>
                </div>
            @endif
        </aside>
        {{-- Main Content --}}
        <main class="flex-1 flex flex-col">
            {{-- Top Navbar --}}
            <header class="bg-white border-b flex items-center justify-between px-8 py-3">
                <div class="flex items-center gap-8">
                    {{-- Vous pouvez ajouter d'autres éléments ici si nécessaire --}}
                </div>
                <div class="flex items-center gap-4">
                    <form>
                        <input type="text" placeholder="Rechercher..."
                            class="rounded-lg border px-3 py-1.5 text-sm focus:ring-violet-400 focus:border-violet-400">
                    </form>
                    <button class="relative">
                        <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" stroke-width="2"
                            viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round"
                                d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6 6 0 10-12 0v3.159c0 .538-.214 1.055-.595 1.436L4 17h5" />
                        </svg>
                        <span class="absolute top-0 right-0 inline-block w-2 h-2 bg-red-500 rounded-full"></span>
                    </button>
                    <img src="https://i.pravatar.cc/32?img=1" alt="Coordinateur"
                        class="w-8 h-8 rounded-full border-2 border-violet-300">
                </div>
            </header>
            {{-- Page Content --}}
            <div class="flex-1 px-8 py-6">
                @yield('content')
            </div>
            <footer class="text-xs text-gray-400 text-center py-2">
                IFRAN<span class="text-violet-600 font-bold">2025</span>
            </footer>
        </main>
    </div>
    @yield('scripts')
</body>

</html>
