<!-- resources/js/Layouts/Layout.vue -->
<script setup>
import Navigation from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import { usePage } from '@inertiajs/vue3';
import {ref, watch} from 'vue';

const page = usePage();
const flashMessage = ref(page.props.flash?.success || null);

watch(() => page.props.flash?.success, (msg) => {
    if (msg) {
        flashMessage.value = msg;
    }
});

const cerrarMensaje = () => {
    flashMessage.value = null;
};
</script>

<template>
    <div class="bg-sky-400">
        <div v-if="flashMessage"
            class="fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg animate-fade-in z-50 flex items-center space-x-2"
        >
            <span>{{ flashMessage }}</span>
            <button
                @click="cerrarMensaje"
                class="text-white text-xl leading-none hover:text-gray-200"
                aria-label="Cerrar"
            >
                &times;
            </button>
        </div>
        <Navigation />
        <main>
            <slot />
        </main>
        <Footer />
    </div>
</template>
