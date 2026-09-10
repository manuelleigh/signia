<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

const form = useForm({
    ruc: '',
    business_name: '',
    environment: 'demo',
    engine_type: 'qpse',
    sol_user: '',
    sol_pass: '',
});

const submit = () => {
    form.post(route('companies.store'));
};
</script>

<template>
    <Head title="Nueva Empresa" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="font-bold text-2xl text-slate-800 tracking-tight">Registrar Nueva Empresa</h2>
                    <p class="text-sm text-slate-500 mt-1">Añade un nuevo cliente RUC a tu agencia para comenzar a emitir comprobantes.</p>
                </div>
                <Link :href="route('companies.index')" class="text-sm font-medium text-slate-600 hover:text-slate-900 bg-white border border-slate-300 px-4 py-2 rounded-lg transition">
                    Volver a la lista
                </Link>
            </div>
        </template>

        <div class="py-10 max-w-4xl mx-auto sm:px-6 lg:px-8">
            <form @submit.prevent="submit" class="space-y-8">
                
                <!-- Sección 1: Datos Generales -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">1</div>
                        <h3 class="text-lg font-semibold text-slate-800">Datos de la Empresa</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">RUC</label>
                            <input v-model="form.ruc" type="text" maxlength="11" placeholder="Ej. 20600000001" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900" required>
                            <div v-if="form.errors.ruc" class="text-red-500 text-xs mt-1">{{ form.errors.ruc }}</div>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Razón Social</label>
                            <input v-model="form.business_name" type="text" placeholder="Ej. Mi Empresa S.A.C." class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900" required>
                            <div v-if="form.errors.business_name" class="text-red-500 text-xs mt-1">{{ form.errors.business_name }}</div>
                        </div>
                    </div>
                </div>

                <!-- Sección 2: Configuración -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">2</div>
                        <h3 class="text-lg font-semibold text-slate-800">Configuración de Emisión</h3>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Motor de Envío</label>
                            <select v-model="form.engine_type" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900">
                                <option value="qpse">Motor PSE - Recomendado</option>
                                <option value="native">Motor Nativo (Certificado Propio)</option>
                            </select>
                            <p class="text-xs text-slate-500 mt-2">
                                <span v-if="form.engine_type === 'qpse'">Delega la firma electrónica a nuestros servidores autorizados.</span>
                                <span v-else>Firma con el certificado digital propio de la empresa.</span>
                            </p>
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Entorno SUNAT</label>
                            <select v-model="form.environment" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900">
                                <option value="demo">Modo Pruebas (Beta)</option>
                                <option value="production">Modo Producción (Real)</option>
                            </select>
                        </div>
                    </div>
                </div>

                <!-- Sección 3: Credenciales SUNAT -->
                <div class="bg-white rounded-2xl shadow-sm border border-slate-100 p-8">
                    <div class="flex items-center gap-3 mb-6">
                        <div class="w-10 h-10 rounded-full bg-blue-50 text-blue-600 flex items-center justify-center font-bold text-lg">3</div>
                        <div>
                            <h3 class="text-lg font-semibold text-slate-800">Credenciales SUNAT</h3>
                            <p class="text-sm text-slate-500">Usuario secundario SOL para envío de comprobantes.</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Usuario SOL (Opcional)</label>
                            <input v-model="form.sol_user" type="text" placeholder="MODDATOS" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-slate-700 mb-1">Clave SOL (Opcional)</label>
                            <input v-model="form.sol_pass" type="password" placeholder="••••••••" class="block w-full rounded-lg border-slate-300 shadow-sm focus:border-blue-500 focus:ring-blue-500 text-slate-900">
                        </div>
                    </div>
                </div>

                <div v-if="form.errors.error" class="bg-red-50 text-red-600 p-4 rounded-lg text-sm border border-red-100">
                    {{ form.errors.error }}
                </div>

                <!-- Botón de Guardar -->
                <div class="flex justify-end pt-4">
                    <button type="submit" :disabled="form.processing" class="inline-flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors disabled:opacity-75 shadow-sm">
                        <svg v-if="form.processing" class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                        Crear Empresa y Registrar
                    </button>
                </div>
            </form>
        </div>
    </AuthenticatedLayout>
</template>
