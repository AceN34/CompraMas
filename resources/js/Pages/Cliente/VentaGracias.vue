<script setup>
import Layout from '@/Layouts/Layout.vue';
import { Head, Link } from '@inertiajs/vue3';

defineOptions({
    layout: Layout
});
</script>

<template>
    <Head title="Gracias por tu compra" />

    <div class="flex flex-col items-center justify-center min-h-screen bg-sky-100 text-black px-4">
        <div class="bg-sky-200 rounded-lg shadow-lg p-10 text-center animate-fade-in max-w-xl w-full">
            <h1 class="text-3xl font-bold mb-4">¡Gracias por tu compra!</h1>
            <p class="mb-6">Hemos recibido tu pedido correctamente. En breve comenzaremos con su procesamiento.</p>
            <Link
                href="/"
                class="bg-blue-500 hover:bg-blue-600 text-white font-semibold px-6 py-2 rounded transition"
            >
                Volver a la tienda
            </Link>
        </div>
    </div>
</template>
