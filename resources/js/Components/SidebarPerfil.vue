<!-- resources/js/Components/PerfilSidebar.vue -->
<script setup>
import { ref } from 'vue';
import { router } from '@inertiajs/vue3';

const abierto = ref(false);

const toggleMenu = () => {
    abierto.value = !abierto.value;
};

const irA = (ruta) => {
    router.visit(ruta);
    abierto.value = false;
};
</script>

<template>
    <aside class="w-full sm:w-60 bg-sky-300 sm:bg-white shadow-lg sm:shadow-none sm:block sm:relative">
        <!-- Botón hamburguesa (solo en móvil) -->
        <div class="sm:hidden flex justify-end p-4">
            <button @click="toggleMenu" class="text-sky-800 text-lg font-bold">
                ☰ Menú
            </button>
        </div>

        <!-- Menú lateral -->
        <nav :class="[
            'sm:block',
            'sm:pt-8 sm:pl-6',
            abierto ? 'block' : 'hidden'
        ]" class="sm:relative bg-sky-200 sm:bg-transparent px-4 py-2 sm:p-0 rounded-md sm:rounded-none">
            <ul class="space-y-4 text-lg font-semibold text-sky-800">
                <li>
                    <button @click="irA('/perfil')" class="hover:text-blue-700 transition">Mi perfil</button>
                </li>
                <li>
                    <button @click="irA('/historial-pedidos')" class="hover:text-blue-700 transition">Historial de pedidos</button>
                </li>
            </ul>
        </nav>
    </aside>
</template>
