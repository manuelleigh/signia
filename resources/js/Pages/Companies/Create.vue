<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';

const form = useForm({
    ruc: '',
    business_name: '',
    environment: 'demo',
    engine_type: 'qpse',
});

const submit = () => {
    form.post(route('companies.store'));
};
</script>

<template>
    <Head title="Nueva Empresa" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">Registrar Nueva Empresa</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg max-w-2xl mx-auto">
                    <form @submit.prevent="submit" class="p-6">
                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">RUC</label>
                            <input v-model="form.ruc" type="text" maxlength="11" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                            <div v-if="form.errors.ruc" class="text-red-500 text-xs mt-1">{{ form.errors.ruc }}</div>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Razón Social</label>
                            <input v-model="form.business_name" type="text" class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline" required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Entorno</label>
                            <select v-model="form.environment" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="demo">Demo</option>
                                <option value="production">Producción</option>
                            </select>
                        </div>

                        <div class="mb-6">
                            <label class="block text-gray-700 text-sm font-bold mb-2">Motor de Envío</label>
                            <select v-model="form.engine_type" class="shadow border rounded w-full py-2 px-3 text-gray-700 leading-tight focus:outline-none focus:shadow-outline">
                                <option value="qpse">Motor PSE (Vía QPSE)</option>
                                <option value="native">Motor Nativo (Certificado Propio)</option>
                            </select>
                        </div>

                        <div class="flex items-center justify-end">
                            <button type="submit" :disabled="form.processing" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded focus:outline-none focus:shadow-outline">
                                Guardar Empresa
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </AuthenticatedLayout>
</template>
