<script setup>
import { ref, onMounted } from 'vue';
import { Head, usePage } from '@inertiajs/vue3';
import FooterAdmin from "@/Components/FooterAdmin.vue";
import HeaderAdmin from "@/Components/HeaderAdmin.vue";
import Sidebar from "@/Components/Sidebar.vue";

const producto = ref({
    nombre: '',
    precio: 0,
    categoria: '',
    cantidad: 0,
    imagen: null,
    lotes: []
});

onMounted(() => {
    producto.value = usePage().props.producto;  // Recuperamos el producto desde los props de Inertia
});
</script>
<template>
    <Head title="Ver Producto"/>
    <div class="min-h-screen bg-sky-300 flex flex-col justify-between text-black">
        <HeaderAdmin />
        <div class="flex">
            <Sidebar />
            <main class="flex-1 p-8 overflow-x-auto bg-sky-300">
                <div class="max-w-5xl mx-auto bg-sky-400 rounded-xl shadow-lg p-8 border border-sky-300">
                    <h2 class="text-3xl font-extrabold text-sky-800 mb-8 border-b-4 border-sky-500 pb-2">
                        Detalles del Producto
                    </h2>

                    <div class="flex flex-col md:flex-row gap-8">
                        <!-- Imagen Producto -->
                        <div class="flex-shrink-0 w-full md:w-1/6 rounded-lg overflow-hidden border border-sky-600 shadow-sm bg-sky-300">
                            <img
                                v-if="producto.imagen"
                                :src="'/images/' + producto.imagen"
                                alt="Imagen del Producto"
                                class="object-cover w-full h-64 md:h-full"
                            />
                            <div v-else class="flex items-center justify-center h-64 text-gray-400 italic">
                                Sin imagen disponible
                            </div>
                        </div>

                        <!-- Info Producto -->
                        <div class="flex-1 space-y-4 text-sky-900">
                            <div class="text-2xl font-semibold border-b pb-2">{{ producto.nombre }}</div>
                            <div class="text-lg">
                                <span class="font-semibold">Precio:</span>
                                <span class="text-sky-700">{{ parseFloat(producto.precio).toFixed(2) }} €</span>
                            </div>
                            <div class="text-lg">
                                <span class="font-semibold">Categoría:</span>
                                <span class="capitalize">{{ producto.categoria }}</span>
                            </div>
                            <div class="text-lg">
                                <span class="font-semibold">Cantidad disponible:</span>
                                <span>{{ producto.cantidad }}</span>
                            </div>
                        </div>
                    </div>

                    <!-- Lotes -->
                    <section class="mt-12">
                        <h3 class="text-2xl font-bold text-sky-700 mb-4 border-b border-sky-400 pb-2">
                            Lotes Asociados
                        </h3>
                        <div class="overflow-x-auto">
                            <table class="min-w-full bg-white rounded-lg shadow-sm border border-sky-300">
                                <thead class="bg-sky-600 text-white uppercase tracking-wide">
                                <tr>
                                    <th class="py-3 px-6 text-left">Código</th>
                                    <th class="py-3 px-6 text-left">Cantidad</th>
                                    <th class="py-3 px-6 text-left">Fecha de Caducidad</th>
                                    <th class="py-3 px-6 text-left">Estado</th>
                                </tr>
                                </thead>
                                <tbody>
                                <tr
                                    v-for="lote in producto.lotes"
                                    :key="lote.codigo"
                                    class="even:bg-sky-50 hover:bg-sky-100 transition-colors duration-150"
                                >
                                    <td class="py-3 px-6">{{ lote.codigo }}</td>
                                    <td class="py-3 px-6">{{ lote.cantidad }}</td>
                                    <td class="py-3 px-6">{{ lote.fecha_caducidad ?? 'No disponible' }}</td>
                                    <td class="py-3 px-6 capitalize font-semibold" :class="{
                                        'text-red-600': lote.estado.toLowerCase() === 'caducado',
                                        'text-green-600': lote.estado.toLowerCase() === 'consumible',
                                      }">
                                        {{ lote.estado }}
                                    </td>
                                </tr>
                                <tr v-if="!producto.lotes.length" class="text-center text-gray-500">
                                    <td colspan="4" class="py-6">No hay lotes asociados a este producto.</td>
                                </tr>
                                </tbody>
                            </table>
                        </div>
                    </section>
                </div>
            </main>

        </div>
        <FooterAdmin />
    </div>
</template>
