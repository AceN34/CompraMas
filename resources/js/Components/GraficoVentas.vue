<script setup>
import { ref, watch, onMounted } from 'vue';
import { usePage, router } from '@inertiajs/vue3';
import { Bar } from 'vue-chartjs';
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

// Props desde el backend
const page = usePage();
const etiquetas = ref(page.props.etiquetas || []);
const cantidades = ref(page.props.cantidades || []);
const filtro = ref(page.props.filtro || 'dia');

const chartData = ref({
    labels: etiquetas.value,
    datasets: [
        {
            label: 'Productos más vendidos',
            backgroundColor: '#0284c7',
            data: cantidades.value,
        },
    ],
});

const chartOptions = {
    responsive: true,
    plugins: {
        legend: {
            display: false,
        },
    },
};

const actualizarGrafico = () => {
    router.get(route('admin.ventas'), { filtro: filtro.value }, { preserveState: true });
};

watch(() => page.props.etiquetas, (n) => {
    etiquetas.value = n;
    chartData.value.labels = n;
});

watch(() => page.props.cantidades, (n) => {
    cantidades.value = n;
    chartData.value.datasets[0].data = n;
});
</script>

<template>
    <div class="p-6 bg-white shadow-md rounded-xl max-w-5xl mx-auto mt-6 border-l-8 border-blue-600">
        <div class="flex items-center justify-between mb-6">
            <select v-model="filtro" @change="actualizarGrafico" class="bg-sky-200 text-sky-800 p-2 rounded">
                <option value="dia">Filtrar por Día</option>
                <option value="mes">Filtrar por Mes</option>
            </select>
        </div>

        <Bar :data="chartData" :options="chartOptions" />
    </div>
</template>
