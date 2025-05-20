<!-- resources/js/Pages/Productos/ProductoDetalle.vue -->
<script setup>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'
import { ref, computed } from 'vue'

const { producto } = defineProps({
    producto: Object
})

const cantidad = ref(1)

defineOptions({ layout: Layout })
</script>

<template>
    <Head :title="producto.nombre" />

    <div class="bg-sky-300 min-h-screen py-10 px-4">
        <div class="max-w-6xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden md:flex animate-fade-in">

            <!-- Imagen -->
            <div class="md:w-1/2 flex justify-center items-center bg-sky-200 p-6">
                <img
                    :src="'/images/' + producto.imagen"
                    :alt="producto.nombre"
                    class="object-contain max-h-[400px] transition-transform duration-300 hover:scale-105"
                />
            </div>

            <!-- Detalles -->
            <div class="md:w-1/2 p-8 space-y-4 bg-sky-100 text-gray-800">
                <div class="flex justify-between items-start">
                    <h1 class="text-3xl font-bold">{{ producto.nombre }}</h1>
                    <a
                        :href="`/productos?categoria=${producto.categoria}`"
                        class="text-blue-700 font-semibold hover:underline hover:text-blue-900 transition"
                    >
                        {{ producto.categoria }}
                    </a>
                </div>

                <p class="text-2xl font-bold text-green-700">
                    {{ producto.precio }} € / Unidad
                </p>

                <!-- Estado del stock -->
                <p
                    v-if="producto.cantidad < 5"
                    class="bg-red-100 text-red-700 px-3 py-1 rounded-full inline-block text-sm font-semibold animate-pulse"
                >
                    ¡Quedan solo {{ producto.cantidad }} unidades!
                </p>
                <p v-else class="text-sm text-gray-600">
                    Cantidad disponible: {{ producto.cantidad }} unidades
                </p>

                <!-- Selector de cantidad -->
                <div class="mt-4 flex items-center gap-4">
                    <label for="cantidad" class="font-semibold">Cantidad:</label>
                    <input
                        id="cantidad"
                        type="number"
                        v-model.number="cantidad"
                        min="1"
                        :max="producto.cantidad"
                        class="w-20 px-3 py-1 rounded border border-sky-400 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>

                <!-- Botón -->
                <button
                    class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-lg transition-transform transform hover:scale-105 flex items-center gap-2"
                >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                        <path d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13l-1.5 7h13l-1.5-7M7 13H5.4M9 21a1 1 0 100-2 1 1 0 000 2zm8 0a1 1 0 100-2 1 1 0 000 2z"/>
                    </svg>
                    Añadir al carrito
                </button>

                <!-- Información adicional -->
                <div class="pt-6 border-t border-gray-300 mt-6 text-sm text-gray-600 space-y-1">
                    <p><strong>Código producto:</strong> {{ producto.id }}</p>
                    <p><strong>Fecha de alta:</strong> {{ new Date(producto.created_at).toLocaleDateString() }}</p>
                    <p><strong>Última actualización:</strong> {{ new Date(producto.updated_at).toLocaleDateString() }}</p>
                </div>
            </div>
        </div>
    </div>
</template>
