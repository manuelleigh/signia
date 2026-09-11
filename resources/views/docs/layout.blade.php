<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="Referencia técnica de la API B2B de Signia.">
    <title>@yield('title', 'Documentación API') · Signia Developers</title>
    @vite(['resources/css/app.css'])
    <style>
        [data-copy-state="copied"] .copy-default { display: none; }
        [data-copy-state="idle"] .copy-success { display: none; }
        .docs-grid { grid-template-columns: 17rem minmax(0, 1fr); }
        @media (min-width: 1280px) { .docs-grid { grid-template-columns: 17rem minmax(0, 1fr) 13rem; } }
    </style>
</head>
<body class="min-h-screen bg-white font-sans text-slate-700 antialiased selection:bg-cyan-100 selection:text-slate-950">
    <header class="fixed inset-x-0 top-0 z-50 h-16 border-b border-slate-200 bg-white/95 backdrop-blur">
        <div class="flex h-full items-center gap-4 px-4 lg:px-6">
            <button id="menu-button" type="button" class="inline-flex h-10 w-10 items-center justify-center rounded-lg border border-slate-200 text-slate-600 lg:hidden" aria-label="Abrir navegación" aria-expanded="false">
                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"><path stroke-linecap="round" d="M4 6h16M4 12h16M4 18h16"/></svg>
            </button>
            <a href="{{ route('docs') }}" class="flex items-center gap-3" aria-label="Signia Developers">
                <img src="{{ asset('img/logo-horizontal.png') }}" alt="Signia" class="h-8 w-auto">
                <span class="hidden border-l border-slate-200 pl-3 text-sm font-semibold text-slate-500 sm:block">Developers</span>
            </a>
            <div class="ml-auto flex items-center gap-3">
                <span class="hidden rounded-full border border-cyan-200 bg-cyan-50 px-3 py-1 text-xs font-bold text-cyan-800 sm:inline-flex">API v1</span>
                <a href="{{ route('login') }}" class="text-sm font-semibold text-slate-600 hover:text-slate-950">Ir al panel</a>
            </div>
        </div>
    </header>

    <div id="menu-overlay" class="fixed inset-0 z-30 hidden bg-slate-950/40 lg:hidden"></div>
    <div class="docs-grid mx-auto grid min-h-screen max-w-[100rem] pt-16">
        <aside id="docs-sidebar" class="fixed bottom-0 left-0 top-16 z-40 w-72 -translate-x-full overflow-y-auto border-r border-slate-200 bg-slate-50 px-4 py-6 transition-transform lg:sticky lg:top-16 lg:h-[calc(100vh-4rem)] lg:w-auto lg:translate-x-0">
            <div class="mb-7 px-3">
                <p class="text-xs font-bold uppercase tracking-[0.18em] text-cyan-700">Referencia API</p>
                <p class="mt-2 text-sm leading-6 text-slate-500">Endpoints documentados según el comportamiento real del servicio.</p>
            </div>

            <nav aria-label="Navegación de documentación" class="space-y-7">
                <div>
                    <p class="px-3 text-xs font-bold uppercase tracking-wider text-slate-400">Primeros pasos</p>
                    <a href="#overview" class="mt-2 block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-white hover:text-slate-950">Descripción general</a>
                    <a href="#environments" class="block rounded-lg px-3 py-2 text-sm font-medium text-slate-600 hover:bg-white hover:text-slate-950">Entornos</a>
                </div>
                <div>
                    <p class="px-3 text-xs font-bold uppercase tracking-wider text-slate-400">Autenticación</p>
                    <a href="{{ route('docs.authentication') }}"
                       aria-current="{{ request()->routeIs('docs.authentication') || request()->routeIs('docs') ? 'page' : 'false' }}"
                       class="mt-2 flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold transition
                              {{ request()->routeIs('docs.authentication') || request()->routeIs('docs') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-white hover:text-slate-950' }}">
                        <span class="rounded px-1.5 py-0.5 font-mono text-[11px]
                                     {{ request()->routeIs('docs.authentication') || request()->routeIs('docs') ? 'bg-emerald-400/15 text-emerald-300' : 'bg-emerald-100 text-emerald-700' }}">POST</span>
                        Obtener token
                    </a>
                </div>
                <div>
                    <p class="px-3 text-xs font-bold uppercase tracking-wider text-slate-400">Comprobantes</p>
                    <a href="{{ route('docs.documents.send') }}"
                       aria-current="{{ request()->routeIs('docs.documents.send') ? 'page' : 'false' }}"
                       class="mt-2 flex items-center gap-2 rounded-lg px-3 py-2.5 text-sm font-semibold transition
                              {{ request()->routeIs('docs.documents.send') ? 'bg-slate-900 text-white shadow-sm' : 'text-slate-600 hover:bg-white hover:text-slate-950' }}">
                        <span class="rounded px-1.5 py-0.5 font-mono text-[11px]
                                     {{ request()->routeIs('docs.documents.send') ? 'bg-emerald-400/15 text-emerald-300' : 'bg-emerald-100 text-emerald-700' }}">POST</span>
                        Emitir comprobante
                    </a>
                    <span class="flex items-center justify-between rounded-lg px-3 py-2 text-sm text-slate-400">Consultar estado <span class="text-[10px] font-bold uppercase">Próximo</span></span>
                </div>
            </nav>
        </aside>

        <main class="min-w-0 px-5 py-10 sm:px-8 lg:px-12 xl:px-16">
            @yield('content')
        </main>

        <aside class="sticky top-16 hidden h-[calc(100vh-4rem)] border-l border-slate-100 px-6 py-10 xl:block">
            <p class="text-xs font-bold uppercase tracking-wider text-slate-400">En esta página</p>
            <nav class="mt-4 space-y-3 text-sm">
                @yield('toc')
            </nav>
        </aside>
    </div>

    <script>
        const menuButton = document.getElementById('menu-button');
        const sidebar = document.getElementById('docs-sidebar');
        const overlay = document.getElementById('menu-overlay');

        function setMenu(open) {
            sidebar.classList.toggle('-translate-x-full', !open);
            overlay.classList.toggle('hidden', !open);
            menuButton.setAttribute('aria-expanded', String(open));
        }

        menuButton?.addEventListener('click', () => setMenu(menuButton.getAttribute('aria-expanded') !== 'true'));
        overlay?.addEventListener('click', () => setMenu(false));
        sidebar?.querySelectorAll('a').forEach(link => link.addEventListener('click', () => setMenu(false)));

        document.querySelectorAll('[data-copy]').forEach(button => {
            button.dataset.copyState = 'idle';
            button.addEventListener('click', async () => {
                const source = document.getElementById(button.dataset.copy);
                if (!source) return;
                await navigator.clipboard.writeText(source.textContent.trim());
                button.dataset.copyState = 'copied';
                window.setTimeout(() => button.dataset.copyState = 'idle', 1600);
            });
        });
    </script>
</body>
</html>
