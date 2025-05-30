<script setup>
import { Head } from '@inertiajs/vue3';
import Layout from '@/Layouts/Layout.vue';
import { route } from 'ziggy-js';
import { Link as InertiaLink } from '@inertiajs/vue3';

const props = defineProps({
    productosMasVendidos: Array,
    ultimosProductos: Array
});

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Inicio" />

    <div class="bg-sky-200 min-h-screen py-6 space-y-16">
        <div class="max-w-6xl mx-auto text-black">
            <h2 class="text-3xl font-bold mb-4 text-center text-sky-800">Top Ventas</h2>
            <div class="carousel w-full h-64">
                <div
                    v-for="(producto, index) in productosMasVendidos"
                    :id="'item' + (index + 1)"
                    :key="producto.id"
                    class="carousel-item w-full justify-center group"
                >
                    <InertiaLink :href="route('productos.detalles', producto.id)" class="w-[50%]">
                        <div class="bg-sky-400 rounded-lg border border-sky-600 shadow justify-center text-center hover:shadow-xl p-4 transition transform flex flex-col h-64">
                            <img
                                :src="'/images/' + producto.imagen"
                                alt="Imagen"
                                class="w-full h-40 object-contain mb-2 rounded-box"
                            />
                            <h3 class="text-lg font-semibold text-gray-800 group-hover:text-blue-700 truncate">
                                {{ producto.nombre }}
                            </h3>
                            <p class="font-bold text-lg text-blue-900">
                                {{ producto.precio }} €
                            </p>
                        </div>
                    </InertiaLink>
                </div>
            </div>

            <!-- Navegación del carrusel -->
            <div class="flex w-full justify-center gap-2 py-2">
                <a
                    v-for="(producto, index) in productosMasVendidos"
                    :key="'link-' + producto.id"
                    :href="'#item' + (index + 1)"
                    class="btn btn-xs"
                >
                    {{ index + 1 }}
                </a>
            </div>
        </div>

        <!-- Información -->
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-10">
            <div class="flex-1 space-y-6 text-center md:text-left animate-fade-in">
                <InertiaLink :href="route('productos.index')">
                    <h1 class="text-5xl font-bold text-primary hover:scale-105 transition-transform">Compra+</h1>
                </InertiaLink>
                <hr class="border-gray-400 w-24 mx-auto md:mx-0" />
                <p class="text-lg text-gray-700">
                    Compra+ es tu tienda de alimentación online donde encontrarás tus productos favoritos:
                    desde esa bebida que tanto te gusta hasta los ingredientes perfectos para tu próxima receta.
                </p>
            </div>
            <div class="flex-1 hidden md:block animate-fade-in">
                <InertiaLink :href="route('productos.index')">
                    <img
                        src="/images/logo.png"
                        class="rounded-lg hover:scale-105 transition-transform duration-300"
                        alt="productos"
                    />
                </InertiaLink>
            </div>
        </div>

        <!-- Últimos productos -->
        <div class="max-w-6xl mx-auto px-6">
            <h2 class="text-3xl font-bold mb-6 text-center text-sky-800">Novedades</h2>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-6 animate-fade-in">
                <div v-for="producto in ultimosProductos" :key="producto.id" class="bg-sky-300 rounded-xl border border-sky-500 shadow-md p-4 text-center hover:shadow-xl hover:scale-[1.03] transition-all duration-300 ease-in-out">
                    <InertiaLink :href="route('productos.detalles', producto.id)">
                        <img :src="'/images/' + producto.imagen" alt="Imagen producto" class="w-full h-40 object-contain mb-2 rounded">
                        <h3 class="italic text-lg font-semibold text-gray-800">{{ producto.nombre }}</h3>
                        <p class="font-bold text-lg mt-1 text-blue-900 group-hover:text-green-600 transition-colors duration-200">
                            {{ producto.precio }} €
                        </p>
                    </InertiaLink>
                </div>
            </div>

            <div class="text-center mt-8">
                <InertiaLink :href="route('productos.index')">
                    <button class="btn bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded transition transform hover:scale-105">
                        Ver más productos
                    </button>
                </InertiaLink>
            </div>
        </div>
    </div>
</template>
