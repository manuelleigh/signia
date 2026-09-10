<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, useForm, Link } from '@inertiajs/vue3';
import { ref } from 'vue';

defineProps({
    companies: Array,
});

const showCertModal = ref(false);
const showInfoModal = ref(false);
const activeCompany = ref(null);

const certForm = useForm({
    certificate: null,
    password: '',
    sol_user: '',
    sol_password: '',
});

const openCertModal = (company) => {
    activeCompany.value = company;
    certForm.reset();
    showCertModal.value = true;
};

const openInfoModal = (company) => {
    activeCompany.value = company;
    showInfoModal.value = true;
};

const submitCert = () => {
    certForm.post(route('certificates.store', activeCompany.value.id), {
        onSuccess: () => {
            showCertModal.value = false;
            alert('Certificado guardado correctamente.');
        },
        onError: (errors) => {
            if(errors.certificate) alert(errors.certificate);
        }
    });
};
</script>

<template>
    <Head title="Empresas (RUCs)" />

    <AuthenticatedLayout>
        <template #header>
            <div class="flex justify-between items-center">
                <h2 class="font-semibold text-xl text-gray-800 leading-tight">Mis Empresas (RUCs)</h2>
                <Link :href="route('companies.create')" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                    + Nueva Empresa
                </Link>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 text-gray-900 overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">RUC</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Razón Social</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Entorno</th>
                                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Motor</th>
                                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <tr v-for="company in companies" :key="company.id">
                                    <td class="px-6 py-4 whitespace-nowrap">{{ company.ruc }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">{{ company.business_name }}</td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span :class="{'bg-green-100 text-green-800': company.environment === 'production', 'bg-yellow-100 text-yellow-800': company.environment === 'demo'}" class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full capitalize">
                                            {{ company.environment }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap uppercase text-sm font-medium">
                                        {{ company.engine_type === 'qpse' ? 'Motor PSE' : 'Motor Nativo' }}
                                        <div v-if="company.engine_type === 'native'">
                                            <span v-if="company.certificate" class="text-xs text-green-600 block mt-1">✔ Cert. Configurado</span>
                                            <span v-else class="text-xs text-red-600 block mt-1">⚠ Falta Certificado</span>
                                        </div>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button @click="openInfoModal(company)" class="text-blue-600 hover:text-blue-900 mr-3">
                                            Información
                                        </button>
                                        <button v-if="company.engine_type === 'native'" @click="openCertModal(company)" class="text-indigo-600 hover:text-indigo-900">
                                            Configurar Cert.
                                        </button>
                                    </td>
                                </tr>
                                <tr v-if="companies.length === 0">
                                    <td colspan="5" class="px-6 py-4 text-center text-gray-500">No hay empresas registradas.</td>
                                </tr>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Información / Edición Visual -->
        <div v-if="showInfoModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showInfoModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                    <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                        <div class="flex justify-between items-center mb-6 border-b pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Información de empresa
                            </h3>
                            <button @click="showInfoModal = false" class="text-gray-400 hover:text-gray-500">
                                <span class="sr-only">Cerrar</span>
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mt-4">
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">RUC</label>
                                <input type="text" :value="activeCompany?.ruc" class="block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-700 sm:text-sm" readonly>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Razón Social</label>
                                <input type="text" :value="activeCompany?.business_name" class="block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-700 sm:text-sm" readonly>
                            </div>
                            
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Entorno</label>
                                <select disabled class="block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-700 sm:text-sm capitalize">
                                    <option>{{ activeCompany?.environment }}</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Tipo de plan</label>
                                <select disabled class="block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-700 sm:text-sm">
                                    <option>Por comprobante</option>
                                </select>
                            </div>
                            
                            <div v-if="activeCompany?.engine_type === 'qpse'">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Usuario (Motor PSE)</label>
                                <input type="text" :value="activeCompany?.qpse_username || 'Generando...'" class="block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-700 sm:text-sm font-mono" readonly>
                            </div>
                            <div v-if="activeCompany?.engine_type === 'qpse'">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Contraseña (Motor PSE)</label>
                                <input type="text" :value="activeCompany?.qpse_password || 'Generando...'" class="block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-700 sm:text-sm font-mono" readonly>
                            </div>

                            <div v-if="activeCompany?.engine_type === 'native'">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Usuario SOL</label>
                                <input type="text" :value="activeCompany?.sol_user || 'No configurado'" class="block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-700 sm:text-sm font-mono" readonly>
                            </div>
                            <div v-if="activeCompany?.engine_type === 'native'">
                                <label class="block text-xs font-medium text-gray-500 mb-1">Clave SOL</label>
                                <input type="password" :value="activeCompany?.sol_pass || ''" placeholder="********" class="block w-full border-gray-300 rounded-md shadow-sm bg-gray-50 text-gray-700 sm:text-sm font-mono" readonly>
                            </div>
                        </div>
                    </div>
                    <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                        <button @click="showInfoModal = false" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                            Aceptar
                        </button>
                    </div>
                </div>
            </div>
        </div>

        <!-- Modal de Certificado -->
        <div v-if="showCertModal" class="fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
            <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" @click="showCertModal = false"></div>
                <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
                    <form @submit.prevent="submitCert">
                        <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Subir Certificado (.pfx)</h3>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Archivo .PFX</label>
                                <input type="file" @input="certForm.certificate = $event.target.files[0]" class="mt-1 block w-full" required accept=".pfx,.p12">
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Contraseña del Certificado</label>
                                <input type="password" v-model="certForm.password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Usuario SOL (Secundario)</label>
                                <input type="text" v-model="certForm.sol_user" placeholder="RUC + USUARIO" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                            <div class="mt-4">
                                <label class="block text-sm font-medium text-gray-700">Clave SOL</label>
                                <input type="password" v-model="certForm.sol_password" class="mt-1 block w-full border-gray-300 rounded-md shadow-sm" required>
                            </div>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                            <button type="submit" :disabled="certForm.processing" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-blue-600 text-base font-medium text-white hover:bg-blue-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                                Guardar Configuración
                            </button>
                            <button type="button" @click="showCertModal = false" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                Cancelar
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
