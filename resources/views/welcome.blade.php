<!DOCTYPE html>
<html lang="es" class="scroll-smooth">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Signia | Facturación Electrónica B2B</title>
    <link rel="icon" href="{{ asset('favicon.ico') }}" type="image/x-icon">
    <!-- Tailwind CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- Phosphor Icons (Professional Icon Set) -->
    <script src="https://unpkg.com/@phosphor-icons/web"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="antialiased bg-slate-50 text-slate-900">
    
    <!-- Navbar -->
    <nav class="bg-white/80 backdrop-blur-md border-b border-slate-200 fixed w-full z-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-20">
                <div class="flex items-center">
                    <img src="{{ asset('img/logo-horizontal.png') }}" alt="Signia" class="h-10 w-auto">
                </div>
                <div class="flex items-center space-x-8">
                    <a href="#arquitectura" class="text-slate-600 hover:text-blue-600 font-semibold transition">Arquitectura</a>
                    <a href="#precios" class="text-slate-600 hover:text-blue-600 font-semibold transition">Paquetes B2B</a>
                    <a href="{{ route('docs') }}" class="text-slate-600 hover:text-blue-600 font-semibold transition">API</a>
                    @auth
                        <a href="{{ url('/dashboard') }}" class="text-blue-600 font-bold">Ir al Panel &rarr;</a>
                    @else
                        <a href="{{ route('login') }}" class="text-slate-600 hover:text-blue-600 font-semibold transition">Ingresar</a>
                        <a href="{{ route('register') }}" class="bg-blue-600 hover:bg-blue-700 text-white px-5 py-2.5 rounded-full font-bold shadow-md shadow-blue-600/20 transition transform hover:-translate-y-0.5">Crear Agencia</a>
                    @endauth
                </div>
            </div>
        </div>
    </nav>

    <!-- Hero Section -->
    <div class="pt-32 pb-24 relative overflow-hidden">
        <div class="absolute top-0 left-1/2 w-full -translate-x-1/2 h-full overflow-hidden -z-10">
            <div class="absolute -top-40 left-1/2 -translate-x-1/2 w-[800px] h-[800px] bg-blue-100 rounded-full blur-3xl opacity-50"></div>
        </div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 text-center">
            <div class="inline-flex items-center px-4 py-2 rounded-full bg-blue-50 border border-blue-100 text-blue-700 font-semibold text-sm mb-8 shadow-sm">
                <i class="ph-fill ph-rocket text-blue-600 mr-2 text-lg animate-pulse"></i>
                La plataforma diseñada para Desarrolladores y Agencias
            </div>
            
            <h1 class="text-5xl md:text-7xl font-extrabold text-slate-900 tracking-tight leading-tight mb-8">
                Facturación B2B <br/>
                <span class="text-transparent bg-clip-text bg-gradient-to-r from-blue-600 to-indigo-600">Escalable y Sin Límites</span>
            </h1>
            
            <p class="mt-4 max-w-2xl text-xl text-slate-600 mx-auto mb-10 leading-relaxed">
                Registra RUCs ilimitados. Consume firmas de una sola bolsa global. Integramos Motor PSE y Motor Nativo (Certificado Propio) en una única API REST.
            </p>
            
            <div class="flex justify-center gap-4">
                <a href="{{ route('register') }}" class="bg-blue-600 text-white px-8 py-4 rounded-full font-bold hover:bg-blue-700 shadow-xl shadow-blue-600/30 transition transform hover:-translate-y-1 text-lg flex items-center">
                    Empezar Ahora <i class="ph-bold ph-arrow-right ml-2"></i>
                </a>
                <a href="#precios" class="bg-white text-slate-800 border border-slate-200 px-8 py-4 rounded-full font-bold hover:bg-slate-50 shadow-sm transition text-lg flex items-center">
                    <i class="ph-bold ph-tag text-slate-400 mr-2"></i> Ver Paquetes
                </a>
            </div>
        </div>
    </div>

    <!-- Stats Section -->
    <div class="py-10 bg-white border-y border-slate-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-8 divide-x divide-slate-100 text-center">
                <div class="flex flex-col items-center">
                    <i class="ph-duotone ph-files text-3xl text-blue-500 mb-2"></i>
                    <p class="text-4xl font-extrabold text-slate-900 tracking-tight">5M+</p>
                    <p class="mt-1 text-sm text-slate-500 uppercase tracking-wider font-bold">XML Procesados</p>
                </div>
                <div class="flex flex-col items-center">
                    <i class="ph-duotone ph-lightning text-3xl text-yellow-500 mb-2"></i>
                    <p class="text-4xl font-extrabold text-slate-900 tracking-tight">< 200ms</p>
                    <p class="mt-1 text-sm text-slate-500 uppercase tracking-wider font-bold">Respuesta API</p>
                </div>
                <div class="flex flex-col items-center">
                    <i class="ph-duotone ph-shield-check text-3xl text-emerald-500 mb-2"></i>
                    <p class="text-4xl font-extrabold text-slate-900 tracking-tight">99.9%</p>
                    <p class="mt-1 text-sm text-slate-500 uppercase tracking-wider font-bold">Uptime SLA</p>
                </div>
                <div class="flex flex-col items-center">
                    <i class="ph-duotone ph-buildings text-3xl text-purple-500 mb-2"></i>
                    <p class="text-4xl font-extrabold text-slate-900 tracking-tight">∞</p>
                    <p class="mt-1 text-sm text-slate-500 uppercase tracking-wider font-bold">RUCs Permitidos</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Arquitectura -->
    <div id="arquitectura" class="py-24 bg-slate-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-20">
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight">Arquitectura Híbrida Única</h2>
                <p class="mt-4 text-xl text-slate-600 max-w-3xl mx-auto">Tú eliges cómo enviar a SUNAT por cada cliente (RUC) que registres en tu panel de Agencia.</p>
            </div>
            
            <div class="grid md:grid-cols-2 gap-10">
                <!-- Motor PSE -->
                <div class="bg-white rounded-3xl p-10 shadow-lg shadow-slate-200/50 border border-slate-100 hover:border-blue-200 transition duration-300">
                    <div class="w-16 h-16 bg-blue-50 rounded-2xl flex items-center justify-center mb-8 border border-blue-100 shadow-inner">
                        <i class="ph-duotone ph-cloud-arrow-up text-blue-600 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-slate-900 mb-4">Motor PSE</h3>
                    <p class="text-slate-600 mb-8 leading-relaxed text-lg">Ideal para clientes pequeños. Usa nuestro certificado digital como Proveedor de Servicios Electrónicos autorizado.</p>
                    <ul class="space-y-4 text-slate-700 font-medium">
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-2xl mr-3"></i> Cero costos en certificados digitales.</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-2xl mr-3"></i> Activación de RUC inmediata.</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-2xl mr-3"></i> Respaldado por operador PSE.</li>
                    </ul>
                </div>
                
                <!-- Motor Nativo -->
                <div class="bg-slate-900 rounded-3xl p-10 shadow-2xl border border-slate-800 relative overflow-hidden hover:border-indigo-500 transition duration-300">
                    <div class="absolute top-0 right-0 bg-indigo-500 text-white text-xs font-bold px-4 py-2 rounded-bl-2xl uppercase tracking-wider">Premium</div>
                    <div class="w-16 h-16 bg-slate-800 rounded-2xl flex items-center justify-center mb-8 border border-slate-700 shadow-inner">
                        <i class="ph-duotone ph-lock-key text-indigo-400 text-4xl"></i>
                    </div>
                    <h3 class="text-2xl font-bold text-white mb-4">Motor Nativo</h3>
                    <p class="text-slate-400 mb-8 leading-relaxed text-lg">Para empresas consolidadas. Signia se conecta de forma directa a los web services de SUNAT aplicando criptografía (XMLDsig) con el certificado propio del cliente.</p>
                    <ul class="space-y-4 text-slate-300 font-medium">
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-indigo-400 text-2xl mr-3"></i> Cargas tu propio archivo .pfx.</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-indigo-400 text-2xl mr-3"></i> Conexión directa a SUNAT/OSE.</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-indigo-400 text-2xl mr-3"></i> Paquetes a mitad de precio.</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>

    <!-- Pricing Section -->
    <div id="precios" class="py-24 bg-white" x-data="{ tab: 'pse' }">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="text-center mb-12">
                <h2 class="text-4xl font-extrabold text-slate-900 tracking-tight mb-4">Precios por Consumo</h2>
                <p class="text-xl text-slate-600 max-w-2xl mx-auto">Registra RUCs ilimitados y consume firmas de un solo paquete global para tu Agencia.</p>
            </div>

            <!-- Tabs Toggle -->
            <div class="flex justify-center mb-16">
                <div class="bg-slate-100 p-1 rounded-full inline-flex relative shadow-inner">
                    <button @click="tab = 'pse'" 
                            :class="{'bg-white shadow-md text-blue-600': tab === 'pse', 'text-slate-500 hover:text-slate-700': tab !== 'pse'}" 
                            class="px-8 py-3 rounded-full font-bold text-sm transition-all duration-300 ease-in-out z-10 w-48 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-cloud-arrow-up text-lg"></i> Motor PSE
                    </button>
                    <button @click="tab = 'nativo'" 
                            :class="{'bg-slate-900 shadow-md text-white': tab === 'nativo', 'text-slate-500 hover:text-slate-700': tab !== 'nativo'}" 
                            class="px-8 py-3 rounded-full font-bold text-sm transition-all duration-300 ease-in-out z-10 w-48 flex items-center justify-center gap-2">
                        <i class="ph-bold ph-lock-key text-lg"></i> Motor Nativo
                    </button>
                </div>
            </div>

            <!-- Tab Content: PSE -->
            <div x-show="tab === 'pse'" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="grid md:grid-cols-4 gap-6">
                <!-- Paquete 1 PSE -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Paquete 1</h3>
                    <div class="flex items-end mb-6">
                        <span class="text-4xl font-extrabold text-blue-600">S/ 70</span>
                    </div>
                    <div class="bg-blue-50 rounded-xl py-3 text-center mb-8 border border-blue-100">
                        <p class="text-blue-800 font-black text-xl">1,000 <span class="text-sm font-semibold uppercase">firmas XML</span></p>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-slate-600 font-medium text-sm">
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> RUCs Ilimitados</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> Sin límite de tiempo</li>
                    </ul>
                    <button class="w-full border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-shopping-cart"></i> Comprar
                    </button>
                </div>

                <!-- Paquete 2 PSE -->
                <div class="bg-white border-2 border-blue-500 rounded-3xl p-8 shadow-xl relative flex flex-col transform md:-translate-y-4">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-blue-500 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm flex items-center gap-1">
                        <i class="ph-fill ph-star"></i> Más Popular
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Paquete 2</h3>
                    <div class="flex items-end mb-6">
                        <span class="text-4xl font-extrabold text-blue-600">S/ 280</span>
                    </div>
                    <div class="bg-blue-50 rounded-xl py-3 text-center mb-8 border border-blue-100">
                        <p class="text-blue-800 font-black text-xl">5,000 <span class="text-sm font-semibold uppercase">firmas XML</span></p>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-slate-600 font-medium text-sm">
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> RUCs Ilimitados</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> Sin límite de tiempo</li>
                    </ul>
                    <button class="w-full bg-blue-600 text-white hover:bg-blue-700 font-bold py-3.5 rounded-xl shadow-lg shadow-blue-600/30 transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-shopping-cart"></i> Comprar
                    </button>
                </div>

                <!-- Paquete 3 PSE -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Paquete 3</h3>
                    <div class="flex items-end mb-6">
                        <span class="text-4xl font-extrabold text-blue-600">S/ 510</span>
                    </div>
                    <div class="bg-blue-50 rounded-xl py-3 text-center mb-8 border border-blue-100">
                        <p class="text-blue-800 font-black text-xl">10,000 <span class="text-sm font-semibold uppercase">firmas XML</span></p>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-slate-600 font-medium text-sm">
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> RUCs Ilimitados</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> Sin límite de tiempo</li>
                    </ul>
                    <button class="w-full border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-shopping-cart"></i> Comprar
                    </button>
                </div>

                <!-- Paquete 4 PSE -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Paquete 4</h3>
                    <div class="flex items-end mb-6">
                        <span class="text-4xl font-extrabold text-blue-600">S/ 2,100</span>
                    </div>
                    <div class="bg-blue-50 rounded-xl py-3 text-center mb-8 border border-blue-100">
                        <p class="text-blue-800 font-black text-xl">50,000 <span class="text-sm font-semibold uppercase">firmas XML</span></p>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-slate-600 font-medium text-sm">
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> RUCs Ilimitados</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> Sin límite de tiempo</li>
                    </ul>
                    <button class="w-full border-2 border-blue-600 text-blue-600 hover:bg-blue-600 hover:text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-shopping-cart"></i> Comprar
                    </button>
                </div>
            </div>

            <!-- Tab Content: Nativo -->
            <div x-show="tab === 'nativo'" style="display: none;" x-transition:enter="transition ease-out duration-300" x-transition:enter-start="opacity-0 transform scale-95" x-transition:enter-end="opacity-100 transform scale-100" class="grid md:grid-cols-4 gap-6">
                <!-- Paquete 1 Nativo -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Paquete 1</h3>
                    <div class="flex items-end mb-6">
                        <span class="text-4xl font-extrabold text-indigo-600">S/ 70</span>
                    </div>
                    <div class="bg-indigo-50 rounded-xl py-3 text-center mb-8 border border-indigo-100">
                        <p class="text-indigo-800 font-black text-xl">2,000 <span class="text-sm font-semibold uppercase">firmas XML</span></p>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-slate-600 font-medium text-sm">
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> RUCs Ilimitados</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> Sin límite de tiempo</li>
                    </ul>
                    <button class="w-full border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-shopping-cart"></i> Comprar
                    </button>
                </div>

                <!-- Paquete 2 Nativo -->
                <div class="bg-white border-2 border-indigo-500 rounded-3xl p-8 shadow-xl relative flex flex-col transform md:-translate-y-4">
                    <div class="absolute top-0 left-1/2 -translate-x-1/2 -translate-y-1/2 bg-indigo-500 text-white px-4 py-1.5 rounded-full text-xs font-bold uppercase tracking-wider shadow-sm flex items-center gap-1">
                        <i class="ph-fill ph-star"></i> Más Popular
                    </div>
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Paquete 2</h3>
                    <div class="flex items-end mb-6">
                        <span class="text-4xl font-extrabold text-indigo-600">S/ 280</span>
                    </div>
                    <div class="bg-indigo-50 rounded-xl py-3 text-center mb-8 border border-indigo-100">
                        <p class="text-indigo-800 font-black text-xl">10,000 <span class="text-sm font-semibold uppercase">firmas XML</span></p>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-slate-600 font-medium text-sm">
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> RUCs Ilimitados</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> Sin límite de tiempo</li>
                    </ul>
                    <button class="w-full bg-indigo-600 text-white hover:bg-indigo-700 font-bold py-3.5 rounded-xl shadow-lg shadow-indigo-600/30 transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-shopping-cart"></i> Comprar
                    </button>
                </div>

                <!-- Paquete 3 Nativo -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Paquete 3</h3>
                    <div class="flex items-end mb-6">
                        <span class="text-4xl font-extrabold text-indigo-600">S/ 510</span>
                    </div>
                    <div class="bg-indigo-50 rounded-xl py-3 text-center mb-8 border border-indigo-100">
                        <p class="text-indigo-800 font-black text-xl">20,000 <span class="text-sm font-semibold uppercase">firmas XML</span></p>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-slate-600 font-medium text-sm">
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> RUCs Ilimitados</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> Sin límite de tiempo</li>
                    </ul>
                    <button class="w-full border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-shopping-cart"></i> Comprar
                    </button>
                </div>

                <!-- Paquete 4 Nativo -->
                <div class="bg-white border border-slate-200 rounded-3xl p-8 shadow-sm hover:shadow-xl hover:-translate-y-1 transition duration-300 flex flex-col">
                    <h3 class="text-xl font-bold text-slate-900 mb-2">Paquete 4</h3>
                    <div class="flex items-end mb-6">
                        <span class="text-4xl font-extrabold text-indigo-600">S/ 2,100</span>
                    </div>
                    <div class="bg-indigo-50 rounded-xl py-3 text-center mb-8 border border-indigo-100">
                        <p class="text-indigo-800 font-black text-xl">100,000 <span class="text-sm font-semibold uppercase">firmas XML</span></p>
                    </div>
                    <ul class="space-y-4 mb-8 flex-1 text-slate-600 font-medium text-sm">
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> RUCs Ilimitados</li>
                        <li class="flex items-center"><i class="ph-fill ph-check-circle text-emerald-500 text-xl mr-3"></i> Sin límite de tiempo</li>
                    </ul>
                    <button class="w-full border-2 border-indigo-600 text-indigo-600 hover:bg-indigo-600 hover:text-white font-bold py-3.5 rounded-xl transition flex items-center justify-center gap-2">
                        <i class="ph-bold ph-shopping-cart"></i> Comprar
                    </button>
                </div>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-slate-900 text-slate-400 py-12 text-center border-t border-slate-800">
        <p class="text-sm">&copy; {{ date('Y') }} Signia Platform - B2B Factoring Engine. Todos los derechos reservados.</p>
    </footer>

</body>
</html>
