<script setup>
import { usePage } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'
import Navigation from '@/Components/Navigation.vue'

defineOptions({
    layout: Layout
})

const page = usePage()
const isAuthenticated = page.props.auth.user !== null
</script>

<template>
    <!-- Usuario NO autenticado -->
    <div v-if="!isAuthenticated" class="bg-sky-200 min-h-screen">
        <div class="flex items-center justify-center justify-self-center min-h-64 min-w-full">
            <img src="/images/imagenInicio.jpg" class="h-96 w-2/3 mt-7 mb-5 rounded-2xl" alt="Logo">
        </div>
        <div class="flex justify-self-center w-2/3">
            <div class="text-black">
                <h1 class="mb-5 text-5xl font-bold text-blue-700">Compra+</h1>
                <div class="w-1/2">
                    <hr class="border-t-2 border-gray-800 "/><br />
                    <p class="mb-5">Compra+ es tu tienda de alimentación online donde encontrarás
                        tus productos favoritos: desde esa bebida que tanto te gusta hasta
                        los ingredientes perfectos para tu próxima receta.</p>
                    <a href="/productos">
                        <button class="btn btn-primary">Ver Productos</button>
                    </a>
                </div>
            </div>
        </div>
    </div>

        <!-- Usuario autenticado -->
        <div v-else class="hero min-h-screen bg-sky-500">
            <div class="p-4 card card-compact bg-base-100 w-96 shadow-xl">
                <figure>
                    <img class="w-1/2 h-1/2" src="/images/administrar.png" alt="Productos" />
                </figure>
                <div class="card-body">
                    <h2 class="card-title">Administrar Tienda</h2>
                    <p>Gestionamos altas, bajas, actualizaciones y borrado de una tabla de productos</p>
                    <div class="card-actions justify-end">
                        <a href="/productos" class="btn btn-primary">Ver Productos</a>
                    </div>
                </div>
            </div>
        </div>
</template>
