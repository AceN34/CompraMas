<script setup>
import { ref, computed, onMounted } from 'vue';
import Layout from "@/Layouts/Layout.vue";
import { Head } from "@inertiajs/vue3";
import { Link as InertiaLink } from '@inertiajs/vue3'
import { route } from "ziggy-js";

const props = defineProps({
    productos: Array,
    search: String,
    categoria: String
});


// Variables para los filtros
const categoriaSeleccionada = ref(null)
const precioMin = ref(0);
const precioMax = ref(100);
const orden = ref('relevancia');
const soloDisponibles = ref(true); // Nuevo: para filtrar solo disponibles

const categorias = computed(() => {
    const todas = props.productos.map(p => p.categoria);
    return [...new Set(todas)];
});

    // Filtros
const productosFiltrados = computed(() => {
    let filtrados = [...props.productos];

    if (categoriaSeleccionada.value) {
        filtrados = filtrados.filter(p => p.categoria === categoriaSeleccionada.value);
    }

    filtrados = filtrados.filter(p => p.precio >= precioMin.value && p.precio <= precioMax.value);

    if (soloDisponibles.value) {
        filtrados = filtrados.filter(p => p.cantidad > 0);
    }

    if (orden.value === 'precioAsc') {
        filtrados.sort((a, b) => a.precio - b.precio);
    } else if (orden.value === 'precioDesc') {
        filtrados.sort((a, b) => b.precio - a.precio);
    } else if (orden.value === 'relevancia') {
        filtrados.sort((a, b) => new Date(b.created_at) - new Date(a.created_at));
    }

    return filtrados;
});

onMounted(() => {
    if (props.categoria) {
        categoriaSeleccionada.value = props.categoria;
    }
});

function limpiarFiltros() {
    categoriaSeleccionada.value = null;
    precioMin.value = 0;
    precioMax.value = 100;
    orden.value = 'relevancia';
    soloDisponibles.value = true;
}

defineOptions({layout: Layout});
</script>
<template>
    <Head title="Productos"/>
    <div class="bg-sky-200 min-h-screen flex flex-col">
        <div class="flex flex-grow px-6 py-6 text-black">
            <!-- Menú Lateral -->
            <aside class="w-1/5 pr-6">
                <div class="bg-sky-100 p-4 rounded-lg shadow">
                    <h2 class="text-xl font-bold mb-4">Categorías</h2>
                    <ul>
                        <li
                            v-for="cat in categorias"
                            :key="cat"
                            class="cursor-pointer py-1 px-2 rounded hover:bg-blue-200 transition-all duration-200"
                            :class="{ 'font-bold text-blue-700 bg-blue-100': categoriaSeleccionada === cat }"
                            @click="categoriaSeleccionada = cat"
                        >
                            {{ cat }}
                        </li>
                    </ul>

                    <div class="mt-6">
                        <h3 class="text-lg font-bold">Rango de precios</h3>
                        <div class="flex space-x-2 mt-2">
                            <input type="number" v-model.number="precioMin" class="w-1/2 p-1 border rounded" />
                            <input type="number" v-model.number="precioMax" class="w-1/2 p-1 border rounded" />
                        </div>

                        <div class="mt-4 flex items-center space-x-2">
                            <input type="checkbox" v-model="soloDisponibles" id="disponibles" />
                            <label for="disponibles" class="text-sm">Solo disponibles</label>
                        </div>

                        <button
                            class="mt-4 w-full bg-red-500 text-white py-2 rounded hover:bg-red-600 transition"
                            @click="limpiarFiltros"
                        >
                            Borrar Filtros
                        </button>
                    </div>
                </div>
            </aside>

            <!-- Productos -->
            <main class="flex-1">
                <!-- Orden -->
                <div class="flex justify-end space-x-2 mb-4">
                    <button
                        v-for="tipo in ['relevancia', 'precioAsc', 'precioDesc']"
                        :key="tipo"
                        class="px-3 py-1 border rounded-full transition-colors duration-200"
                        :class="orden === tipo
                        ? 'bg-blue-600 text-white hover:bg-blue-700'
                        : 'hover:bg-gray-200 text-gray-700 border-gray-300'"
                        @click="orden = tipo"
                    >
                        {{ tipo === 'relevancia' ? 'Relevancia' : tipo === 'precioAsc' ? 'Precio ↑' : 'Precio ↓' }}
                    </button>
                </div>

                <!-- Grid -->
                <div v-if="productosFiltrados.length > 0" class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-6">
                    <div
                        v-for="producto in productosFiltrados"
                        :key="producto.id"
                        class="group bg-sky-300 rounded-xl border border-sky-500 shadow-md p-4 text-center hover:shadow-xl hover:scale-[1.03] transition-all duration-300 ease-in-out"
                    >
                        <InertiaLink
                            :href="route('productos.detalles', producto.id)"
                            class="block bg-sky-300 rounded-lg p-4 text-center transition group"
                        >
                            <div class="overflow-hidden rounded-lg mb-3">
                                <img
                                    :src="'/images/' + producto.imagen"
                                    :alt="producto.nombre"
                                    class="h-40 w-full object-contain transition-transform duration-300 group-hover:scale-110"
                                />
                            </div>
                            <p class="italic text-lg text-gray-700 group-hover:text-blue-800 transition-colors duration-200">
                                {{ producto.nombre }}
                            </p>
                            <p class="font-bold text-lg mt-1 text-blue-900 group-hover:text-green-600 transition-colors duration-200">
                                {{ producto.precio }} €
                            </p>
                        </InertiaLink>
                    </div>
                </div>
                <div v-else class="text-center text-gray-800 mt-12">
                    No hay productos que coincidan con los filtros seleccionados.
                </div>
            </main>
        </div>
    </div>
</template>
