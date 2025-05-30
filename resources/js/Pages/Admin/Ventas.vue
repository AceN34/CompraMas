<script setup>
import {Head, router, usePage} from '@inertiajs/vue3';
import Sidebar from '@/Components/Sidebar.vue';
import HeaderAdmin from '@/Components/HeaderAdmin.vue';
import FooterAdmin from '@/Components/FooterAdmin.vue';
import {Bar} from "vue-chartjs";
import {
    Chart as ChartJS,
    Title,
    Tooltip,
    Legend,
    BarElement,
    CategoryScale,
    LinearScale,
} from 'chart.js';
import {ref, watch} from "vue";
import {route} from "ziggy-js";

ChartJS.register(Title, Tooltip, Legend, BarElement, CategoryScale, LinearScale);

// Props desde el backend
const page = usePage();
const etiquetas = ref(page.props.etiquetas || []);
const cantidades = ref(page.props.cantidades || []);

const chartData = ref({
    labels: etiquetas.value,
    datasets: [
        {
            label: 'Productos vendidos',
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
    <Head title="Productos más vendidos este mes" />
    <div class="min-h-screen flex flex-col justify-between bg-sky-300 text-black">
        <HeaderAdmin />
        <div class="flex">
            <Sidebar />
            <main class="flex-1 p-6 overflow-x-auto">
                <div class="p-6 bg-sky-200 shadow-md rounded-xl max-w-5xl mx-auto mt-6 border-l-8 border-blue-600">
                    <h2 class="text-2xl font-bold text-sky-700 mb-6">Productos más vendidos este mes</h2>
                    <Bar :data="chartData" :options="chartOptions" />
                </div>
            </main>
        </div>
        <FooterAdmin />
    </div>
</template>
