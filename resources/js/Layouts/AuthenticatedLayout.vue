<script setup>
import { ref } from 'vue';
import { Link } from '@inertiajs/vue3';
import Dropdown from '@/Components/Dropdown.vue';
import DropdownLink from '@/Components/DropdownLink.vue';

const showingSidebar = ref(false);
</script>

<template>
    <div class="flex h-screen bg-gray-50 text-gray-900 font-sans">
        
        <!-- Mobile sidebar backdrop -->
        <div v-if="showingSidebar" class="fixed inset-0 z-20 bg-gray-900/50 transition-opacity lg:hidden" @click="showingSidebar = false"></div>

        <!-- Sidebar -->
        <aside :class="[showingSidebar ? 'translate-x-0' : '-translate-x-full']" class="fixed inset-y-0 left-0 z-30 w-64 bg-slate-900 text-white transition-transform duration-300 lg:static lg:translate-x-0 flex flex-col">
            <!-- Logo -->
            <div class="flex items-center justify-center h-20 border-b border-slate-800 px-6">
                <Link :href="route('dashboard')">
                    <img src="/img/logo-horizontal.png" alt="Signia Logo" class="h-8 w-auto brightness-0 invert" />
                </Link>
            </div>

            <!-- Navigation -->
            <nav class="flex-1 px-4 py-6 space-y-2 overflow-y-auto">
                <Link :href="route('dashboard')" :class="{'bg-blue-600 text-white': route().current('dashboard'), 'text-slate-300 hover:bg-slate-800 hover:text-white': !route().current('dashboard')}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium">
                    <i class="ph ph-squares-four text-xl"></i>
                    Dashboard
                </Link>

                <Link :href="route('documents.index')" :class="{'bg-blue-600 text-white': route().current('documents.*'), 'text-slate-300 hover:bg-slate-800 hover:text-white': !route().current('documents.*')}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium">
                    <i class="ph ph-receipt text-xl"></i>
                    Documentos
                </Link>

                <Link :href="route('summaries.index')" :class="{'bg-blue-600 text-white': route().current('summaries.*'), 'text-slate-300 hover:bg-slate-800 hover:text-white': !route().current('summaries.*')}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium">
                    <i class="ph ph-folder-open text-xl"></i>
                    Resúmenes
                </Link>

                <Link :href="route('companies.index')" :class="{'bg-blue-600 text-white': route().current('companies.*'), 'text-slate-300 hover:bg-slate-800 hover:text-white': !route().current('companies.*')}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium">
                    <i class="ph ph-buildings text-xl"></i>
                    Mis Empresas
                </Link>
                
                <a href="/docs" target="_blank" class="flex items-center gap-3 px-4 py-3 rounded-xl text-slate-300 hover:bg-slate-800 hover:text-white transition-colors font-medium">
                    <i class="ph ph-code text-xl"></i>
                    API & Docs
                </a>

                <div v-if="$page.props.auth.user.is_admin" class="pt-6 mt-6 border-t border-slate-800">
                    <p class="px-4 text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Administración</p>
                    <Link :href="route('admin.agencies')" :class="{'bg-blue-600 text-white': route().current('admin.agencies'), 'text-slate-300 hover:bg-slate-800 hover:text-white': !route().current('admin.agencies')}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-colors font-medium">
                        <i class="ph ph-shield-check text-xl"></i>
                        Súper Admin
                    </Link>
                </div>
            </nav>

            <!-- User Menu -->
            <div class="p-4 border-t border-slate-800">
                <Dropdown align="top" width="48">
                    <template #trigger>
                        <button class="flex items-center w-full gap-3 px-4 py-3 rounded-xl hover:bg-slate-800 transition-colors text-left">
                            <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center font-bold text-white uppercase shadow-sm">
                                {{ $page.props.auth.user.name.charAt(0) }}
                            </div>
                            <div class="flex-1 overflow-hidden">
                                <p class="text-sm font-medium truncate">{{ $page.props.auth.user.name }}</p>
                                <p class="text-xs text-slate-400 truncate">{{ $page.props.auth.user.email }}</p>
                            </div>
                            <i class="ph ph-caret-up text-slate-400"></i>
                        </button>
                    </template>
                    <template #content>
                        <DropdownLink :href="route('profile.edit')">Perfil</DropdownLink>
                        <DropdownLink :href="route('logout')" method="post" as="button">Cerrar Sesión</DropdownLink>
                    </template>
                </Dropdown>
            </div>
        </aside>

        <!-- Main Content -->
        <div class="flex-1 flex flex-col min-w-0 overflow-hidden">
            <!-- Mobile Header -->
            <header class="bg-white border-b border-gray-200 lg:hidden flex items-center justify-between h-16 px-4">
                <img src="/img/logo-horizontal.png" alt="Signia Logo" class="h-6 w-auto" />
                <button @click="showingSidebar = true" class="text-gray-500 hover:text-gray-900 focus:outline-none">
                    <i class="ph ph-list text-2xl"></i>
                </button>
            </header>

            <!-- Topbar (Desktop) -->
            <header v-if="$slots.header" class="bg-white border-b border-gray-100 hidden lg:flex items-center h-20 px-8 z-10 sticky top-0">
                <div class="flex-1">
                    <slot name="header" />
                </div>
                <!-- Extra topbar items like notifications could go here -->
                <div class="flex items-center gap-4 text-gray-400">
                    <button class="hover:text-gray-600 transition-colors"><i class="ph ph-bell text-xl"></i></button>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 overflow-y-auto bg-gray-50/50 p-4 lg:p-8">
                <div class="max-w-6xl mx-auto">
                    <slot />
                </div>
            </main>
        </div>
    </div>
</template>

<style>
body {
    font-family: 'Inter', sans-serif;
}
</style>
