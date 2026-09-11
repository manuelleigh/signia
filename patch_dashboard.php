<?php

$vueFile = 'resources/js/Pages/Dashboard.vue';
$vue = file_get_contents($vueFile);

// 1. Add props to script setup
$scriptRegex = '/defineProps\({\s*agency:\s*Object,\s*companiesCount:\s*Number,\s*companiesProdCount:\s*Number,\s*companiesDemoCount:\s*Number,\s*companiesWithCertCount:\s*Number,\s*documentsCount:\s*Number,\s*apiTokens:\s*Array\s*}\);/s';

$newScript = 'import { ref, computed } from \'vue\';
import {
  Chart as ChartJS,
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
} from \'chart.js\';
import { Line } from \'vue-chartjs\';

ChartJS.register(
  CategoryScale,
  LinearScale,
  PointElement,
  LineElement,
  Title,
  Tooltip,
  Legend,
  Filler
);

const props = defineProps({
    agency: Object,
    companiesCount: Number,
    companiesProdCount: Number,
    companiesDemoCount: Number,
    companiesWithCertCount: Number,
    documentsCount: Number,
    chartData: Object,
    apiTokens: Array
});

const chartOptions = {
  responsive: true,
  maintainAspectRatio: false,
  plugins: {
    legend: {
      position: \'bottom\',
    },
  },
  scales: {
    y: {
      beginAtZero: true,
      ticks: {
          stepSize: 1
      }
    }
  }
};

const chartDataObj = computed(() => {
    if (!props.chartData || !props.chartData.labels) return null;
    return {
      labels: props.chartData.labels,
      datasets: [
        {
          label: \'Aceptados\',
          backgroundColor: \'rgba(16, 185, 129, 0.1)\',
          borderColor: \'#10b981\',
          data: props.chartData.accepted,
          fill: true,
          tension: 0.4
        },
        {
          label: \'Rechazados\',
          backgroundColor: \'rgba(239, 68, 68, 0.1)\',
          borderColor: \'#ef4444\',
          data: props.chartData.rejected,
          fill: true,
          tension: 0.4
        },
        {
          label: \'Excepción\',
          backgroundColor: \'rgba(245, 158, 11, 0.1)\',
          borderColor: \'#f59e0b\',
          data: props.chartData.exception,
          fill: true,
          tension: 0.4
        }
      ]
    }
});';

$vue = preg_replace($scriptRegex, $newScript, $vue);


// 2. Add chart template after the grid
$gridEndRegex = '/(<\/div>\s*<!-- API Keys -->)/s';

$chartTemplate = '</div>

        <!-- Gráfico de Emisiones -->
        <div class="bg-white rounded-2xl shadow-sm border border-slate-100 overflow-hidden mb-8">
            <div class="p-6 border-b border-slate-100 bg-slate-50/50">
                <h3 class="text-lg font-bold text-slate-800 flex items-center gap-2">
                    <i class="ph ph-chart-line-up text-indigo-600"></i> Emisiones de los últimos 15 días
                </h3>
            </div>
            <div class="p-6 h-80">
                <Line v-if="chartDataObj" :data="chartDataObj" :options="chartOptions" />
                <div v-else class="h-full flex items-center justify-center text-slate-400">
                    No hay datos suficientes
                </div>
            </div>
        </div>

        <!-- API Keys -->';

$vue = preg_replace($gridEndRegex, $chartTemplate, $vue);

file_put_contents($vueFile, $vue);
echo "Vue template updated.";
