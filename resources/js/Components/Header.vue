<script setup>
import { router } from "@inertiajs/vue3";
import { ref, computed } from "vue";
import { usePage } from '@inertiajs/vue3';

const page = usePage();
const cliente = computed(() => page.props.auth.cliente);
const autenticado = computed(() => cliente.value !== null);

// 🔍 Campo de búsqueda
const search = ref('');

// 🔍 Función para buscar
const buscar = () => {
    router.get('/productos', { search: search.value }, {
        preserveState: true,
        preserveScroll: true,
    });
};
</script>

<template>
    <header class="bg-sky-400 border-b border-blue-600 px-6 py-3">
        <div class="max-w-7xl mx-auto flex items-center justify-between flex-wrap gap-4">

            <!-- Logo -->
            <div class="flex items-center gap-2">
                <a href="/">
                    <img src="/images/logo.png" alt="Compra+" class="h-10" />
                </a>
            </div>

            <!-- Buscador -->
            <div class="flex-1 flex justify-center">
                <form @submit.prevent="buscar" class="flex w-full max-w-md">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Buscar productos..."
                        class="w-full rounded-l-full px-4 py-2 border border-gray-300 focus:outline-none text-black"
                    />
                    <button type="submit" class="bg-black text-white px-4 rounded-r-full hover:bg-gray-800 transition">
                        Buscar
                    </button>
                </form>
            </div>

            <!-- Inicio Sesión y carrito -->
            <div v-if="!autenticado" class="flex items-center gap-4">
                <button
                    class="bg-black text-white text-sm px-4 py-2 rounded hover:bg-gray-800 transition"
                    @click="router.visit('/login')">
                    Iniciar Sesión
                </button>
            </div>

            <div v-if="cliente" class="flex items-center gap-4">
                <p>{{ cliente?.nombre }}</p>
                <button
                    class="bg-black text-white text-sm px-4 py-2 rounded hover:bg-gray-800 transition"
                    @click="router.post('/logout');"
                >
                    Cerrar Sesión
                </button>
                <div class="flex items-center gap-1 text-black">
                    <img src="/images/carrito.png" alt="Carrito" class="w-6 h-6">
                    <span>15,00€</span>
                </div>
            </div>
        </div>
    </header>
</template>
