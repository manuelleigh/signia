<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, useForm, router } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    agency: Object,
    companiesCount: Number,
    companiesProdCount: Number,
    companiesDemoCount: Number,
    companiesWithCertCount: Number,
    documentsCount: Number,
    apiTokens: Array,
});

const form = useForm({
    name: '',
});
const newTokenName = ref('');

const generateToken = () => {
    form.name = newTokenName.value;
    form.post(route('api-keys.store'), {
        preserveScroll: true,
        onSuccess: () => {
            newTokenName.value = '';
        }
    });
};

const deleteToken = (id) => {
    if (confirm('¿Estás seguro de revocar este token? Las aplicaciones que lo usen dejarán de funcionar.')) {
        router.delete(route('api-keys.destroy', id), {
            preserveScroll: true
        });
    }
};
</script>

<template>
    <Head title="Dashboard General" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-2xl text-slate-800 tracking-tight">Dashboard General</h2>
        </template>

        <!-- Welcome Alert -->
        <div v-if="agency" class="bg-gradient-to-r from-blue-600 to-indigo-600 rounded-2xl p-6 mb-8 text-white shadow-lg shadow-blue-900/20 flex items-center justify-between">
            <div class="flex items-center gap-4">
                <div class="bg-white/20 p-3 rounded-xl backdrop-blur-sm">
                    <i class="ph ph-hand-waving text-3xl"></i>
                </div>
                <div>
                    <h3 class="text-xl font-semibold">¡Bienvenido de nuevo, {{ agency.company_name }}!</h3>
                    <p class="text-blue-100 mt-1">Tu agencia está lista para operar. Tienes <strong>{{ agency.balance_qpse }} firmas PSE</strong> y <strong>{{ agency.balance_native }} firmas Nativas</strong> disponibles.</p>
                </div>
            </div>
            <div class="hidden md:block">
                <Link :href="route('companies.index')" class="bg-white text-blue-600 hover:bg-blue-50 px-5 py-2.5 rounded-lg font-semibold transition-colors shadow-sm">
                    Ver mis empresas
                </Link>
            </div>
        </div>
        <div v-else class="bg-yellow-50 border border-yellow-200 rounded-2xl p-6 mb-8 flex items-center gap-4 text-yellow-800">
            <i class="ph ph-warning-circle text-3xl text-yellow-600"></i>
            <div>
                <h3 class="font-bold text-lg">Agencia no vinculada</h3>
                <p>Tu cuenta aún no tiene una Agencia vinculada. Por favor contacta a soporte para que habiliten tu cuenta.</p>
            </div>
        </div>

        <!-- KPI Cards -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            
            <!-- Firmas Consumidas -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-emerald-50 text-emerald-600 rounded-xl group-hover:bg-emerald-600 group-hover:text-white transition-colors">
                        <i class="ph ph-receipt text-2xl"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-slate-500 text-sm font-medium mb-1">Firmas Consumidas</h4>
                    <p class="text-3xl font-bold text-slate-800">{{ documentsCount }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-sm font-medium text-emerald-600 flex items-center gap-1">Histórico de emisión</span>
                </div>
            </div>

            <!-- Bolsa Disponible PSE -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-blue-50 text-blue-600 rounded-xl group-hover:bg-blue-600 group-hover:text-white transition-colors">
                        <i class="ph ph-cloud text-2xl"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-slate-500 text-sm font-medium mb-1">Bolsa Motor PSE</h4>
                    <p class="text-3xl font-bold text-slate-800">{{ agency ? agency.balance_qpse : 0 }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-sm text-slate-500">Firmas restantes</span>
                </div>
            </div>

            <!-- Bolsa Disponible Nativo -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="ph ph-cpu text-2xl"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-slate-500 text-sm font-medium mb-1">Bolsa Motor Nativo</h4>
                    <p class="text-3xl font-bold text-slate-800">{{ agency ? agency.balance_native : 0 }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-sm text-slate-500">Firmas restantes</span>
                </div>
            </div>

            <!-- Total Firmas Adquiridas -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <i class="ph ph-stack text-2xl"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-slate-500 text-sm font-medium mb-1">Total Firmas Adquiridas</h4>
                    <p class="text-3xl font-bold text-slate-800">{{ (agency ? agency.balance_qpse + agency.balance_native : 0) + documentsCount }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-sm font-medium text-slate-500 flex items-center gap-1">Consumidas + Disponibles</span>
                </div>
            </div>

            <!-- RUCs Registrados -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-indigo-50 text-indigo-600 rounded-xl group-hover:bg-indigo-600 group-hover:text-white transition-colors">
                        <i class="ph ph-buildings text-2xl"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-slate-500 text-sm font-medium mb-1">Total RUCs Registrados</h4>
                    <p class="text-3xl font-bold text-slate-800">{{ companiesCount }}</p>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <Link :href="route('companies.index')" class="text-sm font-medium text-indigo-600 hover:text-indigo-800 flex items-center gap-1">Gestionar empresas <i class="ph ph-arrow-right"></i></Link>
                </div>
            </div>

            <!-- Empresas Produccion/Demo -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-purple-50 text-purple-600 rounded-xl group-hover:bg-purple-600 group-hover:text-white transition-colors">
                        <i class="ph ph-rocket-launch text-2xl"></i>
                    </div>
                </div>
                <div class="flex justify-between">
                    <div>
                        <h4 class="text-slate-500 text-sm font-medium mb-1">En Producción</h4>
                        <p class="text-3xl font-bold text-slate-800">{{ companiesProdCount }}</p>
                    </div>
                    <div class="text-right">
                        <h4 class="text-slate-500 text-sm font-medium mb-1">En Demo</h4>
                        <p class="text-3xl font-bold text-slate-800">{{ companiesDemoCount }}</p>
                    </div>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-sm font-medium text-slate-500 flex items-center gap-1">Entornos de operación</span>
                </div>
            </div>

            <!-- Certificados Subidos -->
            <div class="bg-white rounded-2xl p-6 shadow-sm border border-slate-100 hover:shadow-md transition-shadow group">
                <div class="flex justify-between items-start mb-4">
                    <div class="p-3 bg-teal-50 text-teal-600 rounded-xl group-hover:bg-teal-600 group-hover:text-white transition-colors">
                        <i class="ph ph-certificate text-2xl"></i>
                    </div>
                </div>
                <div>
                    <h4 class="text-slate-500 text-sm font-medium mb-1">Certificados Subidos</h4>
                    <p class="text-3xl font-bold text-slate-800">{{ companiesWithCertCount }} <span class="text-lg text-slate-400 font-normal">/ {{ companiesCount }}</span></p>
                </div>
                <div class="mt-4 pt-4 border-t border-slate-100">
                    <span class="text-sm font-medium text-teal-600 flex items-center gap-1">Empresas listas para firmar</span>
                </div>
            </div>

        </div>

        <!-- API Keys -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50 flex flex-col md:flex-row md:items-center justify-between gap-4">
                <div>
                    <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                        <i class="ph ph-key text-blue-600"></i> Tokens de API
                    </h3>
                    <p class="text-sm text-slate-500 mt-1">Administra los tokens estáticos (Sanctum) para tus integraciones.</p>
                </div>
                
                <form @submit.prevent="generateToken" class="flex items-center gap-2">
                    <input v-model="newTokenName" type="text" placeholder="Ej. Producción ERP" class="text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm" required>
                    <button type="submit" :disabled="form.processing" class="bg-slate-900 text-white px-4 py-2 text-sm font-medium rounded-lg hover:bg-slate-800 transition-colors shadow-sm disabled:opacity-50 flex items-center gap-2">
                        <i class="ph ph-plus-circle text-lg"></i> Nuevo Token
                    </button>
                </form>
            </div>

            <div class="p-6">
                <!-- Success Alert -->
                <div v-if="$page.props.flash && $page.props.flash.token" class="mb-6 bg-emerald-50 border border-emerald-200 rounded-xl p-5 flex items-start gap-4">
                    <i class="ph ph-check-circle text-2xl text-emerald-600 mt-0.5"></i>
                    <div>
                        <h4 class="font-bold text-emerald-900">¡Token Generado Exitosamente!</h4>
                        <p class="text-sm text-emerald-700 mt-1 mb-3">Por favor cópielo ahora. Por su seguridad, no volverá a mostrarse:</p>
                        <div class="bg-white border border-emerald-200 rounded-lg p-3 font-mono text-sm break-all shadow-sm flex items-center justify-between">
                            <span class="text-slate-800">{{ $page.props.flash.token }}</span>
                            <!-- Mock copy button -->
                            <button class="text-emerald-600 hover:text-emerald-800"><i class="ph ph-copy"></i></button>
                        </div>
                    </div>
                </div>

                <div class="overflow-x-auto">
                    <table class="w-full text-left border-collapse">
                        <thead>
                            <tr class="border-b border-slate-200 text-sm">
                                <th class="pb-3 font-semibold text-slate-500 uppercase tracking-wider">Nombre</th>
                                <th class="pb-3 font-semibold text-slate-500 uppercase tracking-wider">Creado el</th>
                                <th class="pb-3 font-semibold text-slate-500 uppercase tracking-wider">Último uso</th>
                                <th class="pb-3 font-semibold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
                            </tr>
                        </thead>
                        <tbody class="text-sm divide-y divide-slate-100">
                            <tr v-for="token in apiTokens" :key="token.id" class="hover:bg-slate-50 transition-colors">
                                <td class="py-4 font-medium text-slate-800 flex items-center gap-2">
                                    <i class="ph ph-password text-slate-400"></i> {{ token.name }}
                                </td>
                                <td class="py-4 text-slate-600">{{ new Date(token.created_at).toLocaleDateString() }}</td>
                                <td class="py-4 text-slate-600">
                                    <span v-if="token.last_used_at" class="inline-flex items-center gap-1 text-slate-600"><i class="ph ph-clock text-slate-400"></i> {{ new Date(token.last_used_at).toLocaleString() }}</span>
                                    <span v-else class="inline-flex items-center gap-1 text-slate-400"><i class="ph ph-minus"></i> Nunca usado</span>
                                </td>
                                <td class="py-4 text-right">
                                    <button @click="deleteToken(token.id)" class="text-red-500 hover:text-red-700 font-medium hover:bg-red-50 px-3 py-1.5 rounded-lg transition-colors">
                                        Revocar
                                    </button>
                                </td>
                            </tr>
                            <tr v-if="apiTokens.length === 0">
                                <td colspan="4" class="py-8 text-center text-slate-500">
                                    <div class="flex flex-col items-center justify-center">
                                        <i class="ph ph-key text-4xl text-slate-300 mb-2"></i>
                                        <p>No tienes tokens de API generados.</p>
                                    </div>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
