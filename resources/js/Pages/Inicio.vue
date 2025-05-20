<script setup>
import { Head } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'
import {route} from "ziggy-js";
import { Link as InertiaLink } from '@inertiajs/vue3'

defineOptions({
    layout: Layout
})
</script>

<template>
    <Head title="Home" />

    <!-- Fondo general y separación visual -->
    <div class="bg-sky-200 min-h-screen pt-6">

        <!-- CARRUSEL HERO -->
        <div class="flex items-center justify-center mb-10">
            <div class="carousel w-11/12 max-w-6xl rounded-box shadow-xl">
                <div id="slide1" class="carousel-item relative w-full">
                    <img src="https://img.daisyui.com/images/stock/photo-1625726411847-8cbb60cc71e6.webp" class="w-full object-cover" />
                    <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                        <a href="#slide4" class="btn btn-circle bg-gray-400 hover:bg-gray-300">❮</a>
                        <a href="#slide2" class="btn btn-circle bg-gray-400 hover:bg-gray-300">❯</a>
                    </div>
                </div>
                <div id="slide2" class="carousel-item relative w-full">
                    <img src="https://img.daisyui.com/images/stock/photo-1609621838510-5ad474b7d25d.webp" class="w-full object-cover" />
                    <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                        <a href="#slide1" class="btn btn-circle bg-gray-400 hover:bg-gray-300">❮</a>
                        <a href="#slide3" class="btn btn-circle bg-gray-400 hover:bg-gray-300">❯</a>
                    </div>
                </div>
                <div id="slide3" class="carousel-item relative w-full">
                    <img src="https://img.daisyui.com/images/stock/photo-1414694762283-acccc27bca85.webp" class="w-full object-cover" />
                    <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                        <a href="#slide2" class="btn btn-circle bg-gray-400 hover:bg-gray-300">❮</a>
                        <a href="#slide4" class="btn btn-circle bg-gray-400 hover:bg-gray-300">❯</a>
                    </div>
                </div>
                <div id="slide4" class="carousel-item relative w-full">
                    <img src="https://img.daisyui.com/images/stock/photo-1665553365602-b2fb8e5d1707.webp" class="w-full object-cover" />
                    <div class="absolute flex justify-between transform -translate-y-1/2 left-5 right-5 top-1/2">
                        <a href="#slide3" class="btn btn-circle bg-gray-400 hover:bg-gray-300">❮</a>
                        <a href="#slide1" class="btn btn-circle bg-gray-400 hover:bg-gray-300">❯</a>
                    </div>
                </div>
            </div>
        </div>

        <!-- SECCIÓN INFORMATIVA -->
        <div class="max-w-6xl mx-auto px-6 flex flex-col md:flex-row items-center justify-between gap-10 mb-16">
            <div class="flex-1 space-y-6 text-center md:text-left">
                <InertiaLink :href="route('productos.index')">
                    <h1 class="text-5xl font-bold text-primary hover:scale-105 transition-transform">Compra+</h1>
                </InertiaLink>
                <hr class="border-gray-400 w-24 mx-auto md:mx-0" />
                <p class="text-lg text-gray-700">
                    Compra+ es tu tienda de alimentación online donde encontrarás tus productos favoritos:
                    desde esa bebida que tanto te gusta hasta los ingredientes perfectos para tu próxima receta.
                </p>
                <InertiaLink :href="route('productos.index')">
                    <button class="btn btn-primary hover:scale-105 transition-transform mt-5">Ver Productos</button>
                </InertiaLink>
            </div>
            <div class="flex-1 hidden md:block">
                <InertiaLink :href="route('productos.index')">
                    <img
                        src="/images/logo.png"
                        class="rounded-lg hover:scale-105 transition-transform duration-300"
                        alt="productos"
                    />
                </InertiaLink>
            </div>
        </div>

    </div>
</template>

