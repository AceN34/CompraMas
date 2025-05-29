<script setup>
import { computed } from 'vue';
import {usePage, router, Head} from '@inertiajs/vue3';
import SidebarPerfil from '@/Components/SidebarPerfil.vue';
import Layout from '@/Layouts/Layout.vue';
import {route} from "ziggy-js";

const page = usePage();
const pedido = computed(() => page.props.pedido);

const formatearPrecio = (precio) => `${Number(precio).toFixed(2).replace('.', ',')}€`;
const formatearFecha = (fecha) => new Date(fecha).toLocaleDateString();

const volverHistorial = () => {
    router.get(route('cliente.historialPedidos'));
};
const pagarPedido = () => {
    router.visit(route('venta.pago', { venta: pedido.value.id }));
};
defineOptions({ layout: Layout });
</script>

<template>
    <Head title="Detalle Pedido" />
    <div class="min-h-screen bg-sky-100 flex">
        <SidebarPerfil />
        <main class="flex-grow px-6 py-10 text-black">
            <div class="bg-white rounded-lg shadow-lg p-8 border-l-8 border-blue-600 max-w-6xl mx-auto animate-fade-in">
                <h2 class="text-2xl font-bold text-sky-600 mb-6">Detalles del Pedido #{{ pedido.id }}</h2>

                <div class="mb-6 grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <p><strong>Fecha:</strong> {{ formatearFecha(pedido.created_at) }}</p>
                        <p><strong>Estado:</strong> {{ pedido.estado }}</p>
                        <p><strong>Total:</strong> {{ formatearPrecio(pedido.total) }}</p>
                    </div>
                    <div>
                        <p><strong>Dirección de envío:</strong></p>
                        <p>{{ pedido.direccion }}, {{ pedido.ciudad }}, {{ pedido.codigo_postal }}</p>
                        <p><strong>Teléfono:</strong> {{ pedido.telefono }}</p>
                        <p v-if="pedido.comentarios"><strong>Comentarios:</strong> {{ pedido.comentarios }}</p>
                    </div>
                </div>

                <h3 class="text-xl font-semibold text-sky-700 mb-4">Productos</h3>
                <div class="overflow-hidden rounded-lg border border-gray-300 shadow">
                    <table class="min-w-full bg-white">
                        <thead class="bg-sky-200 text-sky-800 text-left">
                        <tr>
                            <th class="px-4 py-3 border-b">Producto</th>
                            <th class="px-4 py-3 border-b">Cantidad</th>
                            <th class="px-4 py-3 border-b">Precio unitario</th>
                            <th class="px-4 py-3 border-b">Total</th>
                        </tr>
                        </thead>
                        <tbody>
                        <tr
                            v-for="item in pedido.venta_productos"
                            :key="item.id"
                            class="hover:bg-sky-50 transition"
                        >
                            <td class="px-4 py-3 border-b border-gray-300">
                                <div class="flex items-center gap-4">
                                    <img
                                        :src="'/images/' + item.producto.imagen"
                                        alt="Imagen del producto"
                                        class="h-16 w-16 object-contain "
                                    />
                                    <span>{{ item.producto.nombre }}</span>
                                </div>
                            </td>
                            <td class="px-4 py-3 border-b border-gray-300">{{ item.cantidad }}</td>
                            <td class="px-4 py-3 border-b border-gray-300">{{ formatearPrecio(item.precio_unitario) }}</td>
                            <td class="px-4 py-3 border-b border-gray-300">{{ formatearPrecio(item.total) }}</td>
                        </tr>
                        </tbody>
                    </table>
                </div>
                <div class="flex flex-1 justify-center space-x-8">
                    <button
                        @click="volverHistorial"
                        class="mt-6 bg-sky-600 hover:bg-sky-700 text-white py-2 px-4 rounded transition"
                    >
                        Volver al Historial
                    </button>
                    <div v-if="pedido.estado === 'Pendiente'" class="mt-6">
                        <button
                            @click="pagarPedido"
                            class="bg-green-600 hover:bg-green-700 text-white py-2 px-4 rounded transition"
                        >
                            Pagar ahora
                        </button>
                    </div>
                </div>
            </div>
        </main>
    </div>
</template>
