<template>
    <header class="bg-sky-400 border-b border-blue-600 px-6 py-3">
        <div class="max-w-7xl mx-auto flex items-center justify-between flex-wrap gap-4">

            <!-- Logo -->
            <div class="flex items-center gap-2">
                <a href="/login">
                    <img src="/images/logo.png" alt="Compra+" class="h-10" />
                </a>
            </div>

            <!-- Buscador -->
            <div class="flex-1 mx-4 text-black">
                <input
                    type="text"
                    placeholder="Buscar..."
                    class="w-full md:w-96 rounded-full px-4 py-1 border border-gray-300 focus:outline-none"
                />
            </div>

            <!-- Botones de sesión y carrito -->
            <div class="flex items-center gap-4">
                <button
                    class="bg-black text-white text-sm px-4 py-2 rounded hover:bg-gray-800 transition"
                    @click="router.visit('/login')">
                    Iniciar Sesión
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

<script setup>
// No script logic required
import {router} from "@inertiajs/vue3";
</script>

<style scoped>
/* Puedes añadir media queries aquí si necesitas ajustes más finos */
</style>
