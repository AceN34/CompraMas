<script setup>
import { computed } from 'vue';
import {usePage, router, Head} from '@inertiajs/vue3';
import SidebarPerfil from '@/Components/SidebarPerfil.vue';
import Layout from '@/Layouts/Layout.vue';

const page = usePage();
const cliente = computed(() => page.props.auth.cliente);
const pedidos = computed(() => page.props.pedidos);

const formatearFecha = (fecha) => new Date(fecha).toLocaleDateString();
const formatearPrecio = (precio) => `${Number(precio).toFixed(2).replace('.', ',')}€`;

// Función para ir a detalles del pedido
const verDetalles = (id) => {
    router.get(route('cliente.detallePedido', id));
};

defineOptions({ layout: Layout });
</script>

<template>
    <Head title="Historial Pedidos" />
    <div class="min-h-screen bg-sky-100 flex">
        <SidebarPerfil />
        <main class="flex-grow px-6 py-10 justify-center items-center text-black">
            <div class="bg-white rounded-lg shadow-lg p-8 border-l-8 border-blue-600 w-full max-w-6xl mx-auto animate-fade-in">
                <h2 class="text-2xl font-bold text-sky-600 mb-6">Historial de Pedidos</h2>

                <div v-if="pedidos.length === 0" class="text-center text-gray-600 py-10">
                    Aún no has realizado ningún pedido.
                </div>

                <table v-else class="min-w-full bg-white border border-gray-200 rounded-lg overflow-hidden shadow">
                    <thead class="bg-sky-200 text-sky-800 text-left">
                    <tr>
                        <th class="px-4 py-3 border-b">ID</th>
                        <th class="px-4 py-3 border-b">Fecha</th>
                        <th class="px-4 py-3 border-b">Total</th>
                        <th class="px-4 py-3 border-b">Estado</th>
                        <th class="px-4 py-3 border-b">Acciones</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="pedido in pedidos" :key="pedido.id" class="hover:bg-sky-50 transition">
                        <td class="px-4 py-3 border-b">{{ pedido.id }}</td>
                        <td class="px-4 py-3 border-b">{{ formatearFecha(pedido.created_at) }}</td>
                        <td class="px-4 py-3 border-b">{{ formatearPrecio(pedido.total) }}</td>
                        <td class="px-4 py-3 border-b">{{ pedido.estado }}</td>
                        <td class="px-4 py-3 border-b">
                            <button
                                @click="verDetalles(pedido.id)"
                                class="bg-blue-600 hover:bg-blue-700 text-white py-1 px-3 rounded transition"
                            >
                                Ver detalles
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>
        </main>
    </div>
</template>
