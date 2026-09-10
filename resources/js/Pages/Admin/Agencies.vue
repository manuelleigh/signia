<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    agencies: Array,
});

const showBalanceModal = ref(false);
const showCompaniesModal = ref(false);
const activeAgency = ref(null);

const balanceForm = useForm({
    amount: 1000,
    engine_type: 'qpse'
});

const openBalanceModal = (agency) => {
    activeAgency.value = agency;
    balanceForm.reset();
    showBalanceModal.value = true;
};

const openCompaniesModal = (agency) => {
    activeAgency.value = agency;
    showCompaniesModal.value = true;
};

const submitBalance = () => {
    balanceForm.post(route('admin.agencies.balance', activeAgency.value.id), {
        onSuccess: () => {
            showBalanceModal.value = false;
            alert('Saldo recargado correctamente.');
        }
    });
};
</script>

<template>
    <Head title="Admin - Agencias" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">SuperAdmin: Gestión de Agencias</h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Agencia</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Administrador</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RUCs Registrados</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo PSE</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Saldo Nativo</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="agency in agencies" :key="agency.id">
                                    <td class="px-6 py-4 whitespace-nowrap font-medium text-gray-900">{{ agency.company_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm">{{ agency.user?.name }} ({{ agency.user?.email }})</td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-center">
                                        <button @click="openCompaniesModal(agency)" class="bg-blue-100 hover:bg-blue-200 text-blue-800 px-3 py-1 rounded-full text-xs font-bold transition">
                                            {{ agency.companies_count }} Ver RUCs
                                        </button>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-bold" :class="{'text-red-600': agency.balance_qpse < 500, 'text-blue-600': agency.balance_qpse >= 500}">
                                            {{ agency.balance_qpse.toLocaleString() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="text-lg font-bold" :class="{'text-red-600': agency.balance_native < 500, 'text-green-600': agency.balance_native >= 500}">
                                            {{ agency.balance_native.toLocaleString() }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="openBalanceModal(agency)" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 px-3 py-1 rounded">
                                            + Recargar Saldo
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="agencies.length === 0">
                                    <td colspan="6" class="px-6 py-4 text-center text-gray-500">No hay agencias registradas.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Empresas -->
        <div v-if="showCompaniesModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showCompaniesModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                RUCs de la Agencia: <span class="text-blue-600">{{ activeAgency?.company_name }}</span>
                            </h3>
                            <button @click="showCompaniesModal = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Cerrar</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-2 overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">RUC</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Razón Social</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Entorno</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Motor</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Usuario (PSE/SOL)</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Clave (PSE/SOL)</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <tr v-for="comp in activeAgency?.companies" :key="comp.id">
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ comp.ruc }}</td>
                                        <td class="px-4 py-2 text-sm text-gray-900">{{ comp.business_name }}</td>
                                        <td class="px-4 py-2 text-sm">
                                            <span :class="{'bg-green-100 text-green-800': comp.environment === 'production', 'bg-yellow-100 text-yellow-800': comp.environment === 'demo'}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize">
                                                {{ comp.environment }}
                                            </span>
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-900">
                                            {{ comp.engine_type === 'qpse' ? 'Motor PSE' : 'Motor Nativo' }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-900 font-mono">
                                            {{ comp.engine_type === 'qpse' ? (comp.qpse_username || 'N/A') : (comp.sol_user || 'N/A') }}
                                        </td>
                                        <td class="px-4 py-2 text-sm text-gray-900 font-mono">
                                            {{ comp.engine_type === 'qpse' ? (comp.qpse_password || 'N/A') : (comp.sol_pass || 'N/A') }}
                                        </td>
                                    </tr>
                                    <tr v-if="!activeAgency?.companies || activeAgency.companies.length === 0">
                                        <td colspan="6" class="px-4 py-4 text-center text-gray-500 text-sm">Esta agencia aún no ha registrado empresas. (Si acabas de registrar, recarga la página)</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Recarga -->
        <div v-if="showBalanceModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showBalanceModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form @submit.prevent="submitBalance">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Recargar Saldo a: <span class="text-blue-600">{{ activeAgency?.company_name }}</span>
                            </h3>
                            <p class="text-sm text-gray-500 mt-2">Agrega comprobantes (firmas) a la bolsa de esta agencia.</p>
                            
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Tipo de Motor</label>
                                <select v-model="balanceForm.engine_type" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm">
                                    <option value="qpse">Motor PSE</option>
                                    <option value="native">Motor Nativo</option>
                                </select>
                            </div>

                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Cantidad a recargar</label>
                                <input type="number" v-model="balanceForm.amount" min="1" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm text-xl font-bold text-center" required>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" :disabled="balanceForm.processing" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Confirmar Recarga
                            </button>
                            <button type="button" @click="showBalanceModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
