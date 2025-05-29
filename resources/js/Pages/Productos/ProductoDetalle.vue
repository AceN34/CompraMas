<script setup>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'
import { ref } from 'vue'
import { useForm } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const { producto } = defineProps({
    producto: Object
})

const cantidad = ref(1)

const form = useForm({
    producto_id: producto.id,
    cantidad: cantidad,
})

const añadirAlCarrito = () => {
    form.cantidad = cantidad.value
    form.post(route('carrito.agregar'), {
        preserveScroll: true,
        onSuccess: () => {
            cantidad.value = 1
        }
    })
}

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
                    <h1 class="text-3xl font-bold flex-1">{{ producto.nombre }}</h1>
                    <a
                        :href="`/productos?categoria=${producto.categoria}`"
                        class="text-blue-700 font-semibold hover:underline hover:text-blue-900 transition text-right w-40"
                    >
                        {{ producto.categoria }}
                    </a>
                </div>

                <p class="text-2xl font-bold text-green-700">
                    {{ producto.precio }} € / Unidad
                </p>

                <!-- Estado del stock -->
                <div v-if="producto.cantidad <= 0" class="bg-red-200 text-red-800 text-sm px-3 py-1 rounded-full inline-block font-semibold">
                    Producto agotado
                </div>
                <p
                    v-else-if="producto.cantidad <= 5"
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
                        :disabled="producto.cantidad <= 0"
                        class="w-20 px-3 py-1 rounded border border-sky-400 bg-white shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 disabled:bg-gray-200 disabled:cursor-not-allowed"
                    />
                </div>

                <!-- Botón -->
                <button
                    class="mt-6 bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg font-semibold shadow-lg transition-transform transform hover:scale-105 flex items-center gap-3 disabled:bg-gray-400 disabled:cursor-not-allowed"
                    @click="añadirAlCarrito"
                    :disabled="producto.cantidad <= 0"
                >
                    <img src="/images/carrito.png" alt="Carrito" class="w-6 h-6" />
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
        <!-- Botón para volver con función java -->
        <a
            href="javascript:history.back()"
            class="fixed bottom-6 left-6 bg-sky-100 border border-sky-400 text-sky-700 hover:bg-sky-200 font-semibold px-4 py-2 rounded-full shadow-lg transition-all flex items-center gap-2 z-50"
        >
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
            </svg>
            Volver
        </a>
    </div>
</template>
