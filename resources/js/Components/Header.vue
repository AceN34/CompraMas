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
                    <svg
                        class="w-6 h-6"
                        fill="none"
                        stroke="currentColor"
                        stroke-width="1.5"
                        viewBox="0 0 24 24"
                        xmlns="http://www.w3.org/2000/svg"
                    >
                        <path
                            stroke-linecap="round"
                            stroke-linejoin="round"
                            d="M2.25 3h1.386a.75.75 0 01.736.59l1.595 7.978a.75.75 0 00.736.59h10.679a.75.75 0 00.736-.59l1.595-7.978a.75.75 0 01.736-.59h1.386M10.5 20.25a.75.75 0 100-1.5.75.75 0 000 1.5zm6.75 0a.75.75 0 100-1.5.75.75 0 000 1.5z"
                        />
                    </svg>
                    <span>15,00€</span>
                </div>
            </div>
        </div>
    </header>
</template>
