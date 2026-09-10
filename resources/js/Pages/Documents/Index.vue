<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import { Head, Link, router } from '@inertiajs/vue3';
import { ref, watch } from 'vue';

const props = defineProps({
    documents: Object,
    companies: Array,
    filters: Object,
});

const filters = ref({
    period: props.filters.period || 'month',
    month: props.filters.month || '',
    date: props.filters.date || '',
    month_start: props.filters.month_start || '',
    month_end: props.filters.month_end || '',
    date_start: props.filters.date_start || '',
    date_end: props.filters.date_end || '',
    document_type: props.filters.document_type || '',
    environment: props.filters.environment || '',
    company_id: props.filters.company_id || ''
});

const applyFilters = () => {
    router.get(route('documents.index'), filters.value, { preserveState: true, preserveScroll: true, replace: true });
};

// Auto-apply when specific dropdowns change
watch(() => filters.value.period, applyFilters);
watch(() => filters.value.document_type, applyFilters);
watch(() => filters.value.environment, applyFilters);
watch(() => filters.value.company_id, applyFilters);

const selectedDocument = ref(null);
const showingSlideover = ref(false);
const activeTab = ref('informacion');

const openDocument = (doc) => {
    selectedDocument.value = doc;
    activeTab.value = 'informacion';
    showingSlideover.value = true;
};

const closeDocument = () => {
    showingSlideover.value = false;
    setTimeout(() => { selectedDocument.value = null; }, 300);
};

const getDocumentTypeName = (code) => {
    const types = { '01': 'Factura', '03': 'Boleta', '07': 'Nota de Crédito', '08': 'Nota de Débito', '09': 'Guía de Remisión' };
    return types[code] || 'Comprobante (' + code + ')';
};

const formatDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleString();
};

const formatShortDate = (dateString) => {
    if (!dateString) return '';
    return new Date(dateString).toLocaleDateString();
};
</script>

<template>
    <Head title="Documentos Emitidos" />

    <AuthenticatedLayout>
        <template #header>
            <h2 class="font-bold text-2xl text-slate-800 tracking-tight">Documentos</h2>
        </template>

        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            
            <!-- Filters -->
            <div class="p-4 sm:p-6 border-b border-slate-100 bg-slate-50/50 flex flex-wrap items-end gap-4">
                
                <!-- Period Selector -->
                <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Periodo</label>
                    <select v-model="filters.period" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        <option value="month">Por mes</option>
                        <option value="date">Por fecha</option>
                        <option value="between_months">Entre meses</option>
                        <option value="between_dates">Entre fechas</option>
                    </select>
                </div>

                <!-- Dynamic Date Inputs based on Period -->
                <div v-if="filters.period === 'month'" class="w-full sm:w-auto flex-1 min-w-[150px]">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Mes del</label>
                    <input type="month" v-model="filters.month" @change="applyFilters" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                </div>
                <div v-else-if="filters.period === 'date'" class="w-full sm:w-auto flex-1 min-w-[150px]">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Fecha</label>
                    <input type="date" v-model="filters.date" @change="applyFilters" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                </div>
                <template v-else-if="filters.period === 'between_months'">
                    <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Mes del</label>
                        <input type="month" v-model="filters.month_start" @change="applyFilters" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    </div>
                    <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Mes al</label>
                        <input type="month" v-model="filters.month_end" @change="applyFilters" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    </div>
                </template>
                <template v-else-if="filters.period === 'between_dates'">
                    <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Fecha del</label>
                        <input type="date" v-model="filters.date_start" @change="applyFilters" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    </div>
                    <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                        <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Fecha al</label>
                        <input type="date" v-model="filters.date_end" @change="applyFilters" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                    </div>
                </template>

                <!-- Document Type -->
                <div class="w-full sm:w-auto flex-1 min-w-[150px]">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tipo de documento</label>
                    <select v-model="filters.document_type" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        <option value="">Todos</option>
                        <option value="01">Factura</option>
                        <option value="03">Boleta</option>
                        <option value="07">Nota de Crédito</option>
                        <option value="08">Nota de Débito</option>
                    </select>
                </div>
                
                <!-- Environment -->
                <div class="w-full sm:w-auto flex-1 min-w-[120px]">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Tipo de entorno</label>
                    <select v-model="filters.environment" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        <option value="">Todos</option>
                        <option value="demo">Demo</option>
                        <option value="production">Producción</option>
                    </select>
                </div>

                <!-- Company -->
                <div class="w-full lg:w-auto flex-[2] min-w-[200px]">
                    <label class="block text-xs font-semibold text-slate-500 uppercase tracking-wider mb-2">Empresa</label>
                    <select v-model="filters.company_id" class="w-full text-sm rounded-lg border-slate-300 focus:border-blue-500 focus:ring-blue-500 shadow-sm">
                        <option value="">Todos</option>
                        <option v-for="company in companies" :key="company.id" :value="company.id">{{ company.business_name }}</option>
                    </select>
                </div>
            </div>

            <!-- Table -->
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-slate-200 text-sm bg-white">
                            <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider">Fecha de emisión</th>
                            <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider">Entorno</th>
                            <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider">Empresa</th>
                            <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider">Tipo</th>
                            <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider">Serie</th>
                            <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider">Número</th>
                            <th class="px-6 py-4 font-semibold text-slate-500 uppercase tracking-wider text-right">Acciones</th>
                        </tr>
                    </thead>
                    <tbody class="text-sm divide-y divide-slate-100">
                        <tr v-for="doc in documents.data" :key="doc.id" class="hover:bg-slate-50 transition-colors">
                            <td class="px-6 py-4 text-slate-600">{{ formatShortDate(doc.created_at) }}</td>
                            <td class="px-6 py-4">
                                <span v-if="doc.company?.environment === 'production'" class="bg-blue-50 text-blue-600 px-2.5 py-1 rounded-md text-xs font-medium">Producción</span>
                                <span v-else class="bg-purple-50 text-purple-600 px-2.5 py-1 rounded-md text-xs font-medium">Demo</span>
                            </td>
                            <td class="px-6 py-4">
                                <div class="font-medium text-slate-800">{{ doc.company?.business_name || 'Desconocida' }}</div>
                                <div class="text-xs text-slate-400 mt-0.5">{{ doc.company?.ruc }}</div>
                            </td>
                            <td class="px-6 py-4 text-slate-600">{{ getDocumentTypeName(doc.document_type) }}</td>
                            <td class="px-6 py-4 text-slate-600 font-mono">{{ doc.serie }}</td>
                            <td class="px-6 py-4 text-slate-600 font-mono">{{ doc.number }}</td>
                            <td class="px-6 py-4 text-right">
                                <button @click="openDocument(doc)" class="text-blue-600 hover:text-blue-800 p-2 hover:bg-blue-50 rounded-lg transition-colors">
                                    <i class="ph ph-list-dashes text-xl"></i>
                                </button>
                            </td>
                        </tr>
                        <tr v-if="documents.data.length === 0">
                            <td colspan="7" class="py-12 text-center text-slate-500">
                                <i class="ph ph-receipt text-4xl text-slate-300 mb-3 block"></i>
                                No se encontraron documentos con estos filtros.
                            </td>
                        </tr>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div v-if="documents.links.length > 3" class="px-6 py-4 border-t border-slate-100 bg-white flex items-center justify-between">
                <span class="text-sm text-slate-500">
                    Mostrando {{ documents.from || 0 }} a {{ documents.to || 0 }} de {{ documents.total }} filas
                </span>
                <div class="flex gap-1">
                    <template v-for="(link, k) in documents.links" :key="k">
                        <div v-if="link.url === null" class="px-3 py-1.5 text-sm text-slate-400 border border-slate-200 rounded cursor-not-allowed" v-html="link.label"></div>
                        <Link v-else :href="link.url" :class="{'bg-blue-50 border-blue-200 text-blue-600': link.active, 'text-slate-600 border-slate-200 hover:bg-slate-50': !link.active}" class="px-3 py-1.5 text-sm border rounded transition-colors" v-html="link.label"></Link>
                    </template>
                </div>
            </div>
        </div>

        <!-- Slide-over Modal -->
        <div v-if="showingSlideover" class="relative z-50" aria-labelledby="slide-over-title" role="dialog" aria-modal="true">
            <div class="fixed inset-0 bg-slate-900/40 backdrop-blur-sm transition-opacity" @click="closeDocument"></div>

            <div class="fixed inset-0 overflow-hidden">
                <div class="absolute inset-0 overflow-hidden">
                    <div class="pointer-events-none fixed inset-y-0 right-0 flex max-w-full pl-10 sm:pl-16">
                        <div class="pointer-events-auto w-screen max-w-md transform transition-all shadow-2xl">
                            <div class="flex h-full flex-col overflow-y-scroll bg-white">
                                
                                <!-- Header -->
                                <div class="px-6 py-6 sm:px-8 bg-slate-50 border-b border-slate-100 flex items-center justify-between">
                                    <h2 class="text-xl font-bold text-slate-800" id="slide-over-title">
                                        Comprobante: {{ selectedDocument?.serie }}-{{ selectedDocument?.number }}
                                    </h2>
                                    <button @click="closeDocument" type="button" class="rounded-md text-slate-400 hover:text-slate-600 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        <i class="ph ph-x text-2xl"></i>
                                    </button>
                                </div>

                                <!-- Tabs -->
                                <div class="border-b border-slate-200">
                                    <nav class="-mb-px flex px-6 sm:px-8 space-x-8" aria-label="Tabs">
                                        <button @click="activeTab = 'informacion'" :class="[activeTab === 'informacion' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700']" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                                            Información
                                        </button>
                                        <button @click="activeTab = 'seguimiento'" :class="[activeTab === 'seguimiento' ? 'border-blue-500 text-blue-600' : 'border-transparent text-slate-500 hover:border-slate-300 hover:text-slate-700']" class="whitespace-nowrap border-b-2 py-4 px-1 text-sm font-medium">
                                            Seguimiento
                                        </button>
                                    </nav>
                                </div>

                                <!-- Content -->
                                <div class="flex-1 px-6 py-6 sm:px-8">
                                    
                                    <!-- Informacion Tab -->
                                    <div v-show="activeTab === 'informacion'" class="space-y-6 text-sm text-slate-600">
                                        <div><span class="text-slate-400 block text-xs font-semibold uppercase mb-1">Fecha de emisión</span> <span class="text-slate-800 font-medium">{{ formatShortDate(selectedDocument?.created_at) }}</span></div>
                                        
                                        <div><span class="text-slate-400 block text-xs font-semibold uppercase mb-1">Estado</span> 
                                            <span v-if="selectedDocument?.status === 'accepted'" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-emerald-50 text-emerald-700 border border-emerald-200">Aceptado</span>
                                            <span v-else-if="selectedDocument?.status === 'exception' || selectedDocument?.status === 'rejected'" class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-red-50 text-red-700 border border-red-200">Rechazado</span>
                                            <span v-else class="inline-flex items-center px-2 py-1 rounded text-xs font-medium bg-amber-50 text-amber-700 border border-amber-200">Pendiente / Ticket</span>
                                        </div>

                                        <div><span class="text-slate-400 block text-xs font-semibold uppercase mb-1">Fecha de creación</span> <span class="text-slate-800 font-medium">{{ formatDate(selectedDocument?.created_at) }}</span></div>
                                        
                                        <div><span class="text-slate-400 block text-xs font-semibold uppercase mb-1">Empresa Emisora</span> <span class="text-slate-800 font-medium">{{ selectedDocument?.company?.business_name }}</span></div>
                                        
                                        <div class="pt-4 mt-6 border-t border-slate-100">
                                            <span class="text-slate-400 block text-xs font-semibold uppercase mb-3">Descargas</span>
                                            <div class="flex gap-3">
                                                <a :href="selectedDocument?.xml_path ? '/storage/' + selectedDocument.xml_path : '#'" target="_blank" class="px-4 py-2 border border-blue-200 text-blue-600 rounded-lg hover:bg-blue-50 font-medium flex items-center gap-2 transition-colors" :class="{'opacity-50 cursor-not-allowed pointer-events-none': !selectedDocument?.xml_path}">
                                                    <i class="ph ph-file-code"></i> XML
                                                </a>
                                                <a :href="selectedDocument?.cdr_path ? '/storage/' + selectedDocument.cdr_path : '#'" target="_blank" class="px-4 py-2 border border-blue-200 text-blue-600 rounded-lg hover:bg-blue-50 font-medium flex items-center gap-2 transition-colors" :class="{'opacity-50 cursor-not-allowed pointer-events-none': !selectedDocument?.cdr_path}">
                                                    <i class="ph ph-file-zip"></i> CDR
                                                </a>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Seguimiento Tab -->
                                    <div v-show="activeTab === 'seguimiento'">
                                        <div class="flow-root">
                                            <ul role="list" class="-mb-8">
                                                <!-- Send / Accept event -->
                                                <li>
                                                    <div class="relative pb-8">
                                                        <span class="absolute left-4 top-4 -ml-px h-full w-0.5 bg-slate-200" aria-hidden="true"></span>
                                                        <div class="relative flex space-x-3">
                                                            <div>
                                                                <span class="h-8 w-8 rounded-full bg-emerald-500 flex items-center justify-center ring-8 ring-white">
                                                                    <i class="ph ph-paper-plane-tilt text-white"></i>
                                                                </span>
                                                            </div>
                                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                                <div>
                                                                    <p class="text-sm font-semibold text-slate-800">send / accept</p>
                                                                    <p class="mt-1 text-sm text-slate-500">Mensaje: El comprobante número {{ selectedDocument?.serie }}-{{ selectedDocument?.number }} ha sido aceptado.</p>
                                                                    <p class="mt-1 text-xs text-slate-400">Usuario: {{ selectedDocument?.company?.business_name }} | Código: 0</p>
                                                                </div>
                                                                <div class="whitespace-nowrap text-right text-xs text-slate-400">
                                                                    {{ formatDate(selectedDocument?.created_at) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>

                                                <!-- Sign XML event -->
                                                <li>
                                                    <div class="relative pb-8">
                                                        <div class="relative flex space-x-3">
                                                            <div>
                                                                <span class="h-8 w-8 rounded-full bg-emerald-500 flex items-center justify-center ring-8 ring-white">
                                                                    <i class="ph ph-pen-nib text-white"></i>
                                                                </span>
                                                            </div>
                                                            <div class="flex min-w-0 flex-1 justify-between space-x-4 pt-1.5">
                                                                <div>
                                                                    <p class="text-sm font-semibold text-slate-800">signXml</p>
                                                                    <p class="mt-1 text-sm text-slate-500">Mensaje: XML firmado correctamente.</p>
                                                                    <p class="mt-1 text-xs text-slate-400">Motor: {{ selectedDocument?.company?.engine_type === 'qpse' ? 'Motor PSE' : 'Nativo SUNAT' }}</p>
                                                                </div>
                                                                <div class="whitespace-nowrap text-right text-xs text-slate-400">
                                                                    {{ formatDate(selectedDocument?.created_at) }}
                                                                </div>
                                                            </div>
                                                        </div>
                                                    </div>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </AuthenticatedLayout>
</template>
