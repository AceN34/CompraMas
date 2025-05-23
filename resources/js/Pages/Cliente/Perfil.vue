<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { computed } from 'vue';
import Header from '@/Components/Header.vue';
import Footer from '@/Components/Footer.vue';

const page = usePage();
const cliente = computed(() => page.props.auth.cliente);

const irAEditarPerfil = () => {
    router.visit('/perfil/editar');
};

const irACambiarContrasena = () => {
    router.visit('/perfil/cambiar-contrasena');
};
</script>

<template>
    <div class="min-h-screen bg-sky-100 flex flex-col">
        <Header />

        <main class="flex flex-1">
            <!-- Menú lateral -->
            <aside class="w-64 bg-sky-200 p-6 border-r border-blue-300">
                <h2 class="text-xl font-bold text-blue-800 mb-4">Menú</h2>
                <ul class="space-y-2">
                    <li>
                        <a href="/perfil" class="block text-blue-900 hover:text-blue-600 font-medium">Mi perfil</a>
                    </li>
                    <li>
                        <span class="block text-gray-500 cursor-not-allowed font-medium">Historial de pedidos</span>
                    </li>
                </ul>
            </aside>

            <!-- Contenido principal -->
            <section class="flex-1 p-10">
                <div class="bg-white rounded-lg shadow-md p-8 max-w-2xl mx-auto">
                    <h2 class="text-2xl font-bold text-sky-600 mb-6">Datos de la cuenta</h2>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Nombre:</label>
                        <p class="text-lg text-gray-900">{{ cliente?.nombre }}</p>
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 font-medium">Email:</label>
                        <p class="text-lg text-gray-900">{{ cliente?.email }}</p>
                    </div>

                    <div class="mb-6">
                        <label class="block text-gray-700 font-medium">Fecha de registro:</label>
                        <p class="text-lg text-gray-900">{{ new Date(cliente?.created_at).toLocaleDateString() }}</p>
                    </div>

                    <div class="flex flex-col sm:flex-row gap-4 mt-6">
                        <button @click="irAEditarPerfil"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition w-full sm:w-auto">
                            Editar Perfil
                        </button>

                        <button @click="irACambiarContrasena"
                                class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition w-full sm:w-auto">
                            Cambiar Contraseña
                        </button>
                    </div>
                </div>
            </section>
        </main>
        <Footer />
    </div>
</template>
