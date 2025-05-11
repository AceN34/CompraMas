<script setup>
import { usePage } from '@inertiajs/vue3'
import Layout from '@/Layouts/Layout.vue'

defineOptions({
    layout: Layout
})

const page = usePage()
const isAuthenticated = page.props.auth.user !== null
</script>

<template>
    <div>
        <!-- Usuario NO autenticado -->
        <div v-if="!isAuthenticated" class="hero min-h-screen"
             style="background-image: url('https://png.pngtree.com/background/20230519/original/pngtree-an-asian-shop-in-a-city-at-night-picture-image_2663090.jpg');">
            <div class="hero-overlay bg-opacity-60"></div>
            <div class="hero-content text-center text-neutral-content">
                <div class="max-w-md">
                    <h1 class="mb-5 text-5xl font-bold">Tienda</h1>
                    <p class="mb-5">Aquí podrás encontrar productos</p>
                    <hr />
                    <br />
                    <p class="mb-5">Regístrate para acceder a las opciones de gestión</p>
                    <a href="/login">
                        <button class="btn btn-primary">Login</button>
                    </a>
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
    </div>
</template>
