<script setup>
import ApplicationLogo from '@/Components/ApplicationLogo.vue';
import { Link } from '@inertiajs/vue3';
</script>

<template>
        <div class="min-h-screen flex">
        <!-- Left Side: Form -->
        <div class="flex-1 flex flex-col justify-center py-12 px-4 sm:px-6 lg:flex-none lg:px-20 xl:px-24 bg-white">
            <div class="mx-auto w-full max-w-sm lg:w-96">
                <div>
                    <Link href="/">
                        <img src="/img/logo-horizontal.png" alt="Signia B2B" class="h-12 w-auto" />
                    </Link>
                    <h2 class="mt-8 text-2xl font-bold leading-9 tracking-tight text-gray-900">
                        Plataforma B2B
                    </h2>
                </div>

                <div class="mt-8">
                    <slot />
                </div>
            </div>
        </div>

        <!-- Right Side: Graphic/Image -->
        <div class="relative hidden w-0 flex-1 lg:block bg-slate-900">
            <div class="absolute inset-0 h-full w-full bg-cover bg-center" style="background-image: url('/img/auth-bg.jpg');">
                <!-- Overlay oscuro para dar contraste -->
                <div class="absolute inset-0 bg-blue-900/40 mix-blend-multiply"></div>
                <!-- Gradiente sutil abajo -->
                <div class="absolute inset-0 bg-gradient-to-t from-slate-900/80 via-transparent to-transparent"></div>
            </div>
            <div class="absolute inset-0 flex flex-col justify-end p-12 text-white pb-20">
                <h3 class="text-4xl font-bold text-white mb-2 shadow-sm">Escala sin límites</h3>
                <p class="text-blue-50 text-xl font-medium shadow-sm">Registra múltiples agencias y clientes bajo una misma API potente y robusta.</p>
            </div>
        </div>
    </div>
</template>
