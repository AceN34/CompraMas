<script setup>
import { ref, watchEffect } from 'vue';
import { usePage } from '@inertiajs/vue3';

const page = usePage();

const flashMensaje = ref(null);
const tipo = ref('success');
const visible = ref(false);
const ocultando = ref(false);
let temporizador = null;

const cerrarMensaje = () => {
    ocultando.value = true;
    setTimeout(() => {
        visible.value = false;
        flashMensaje.value = null;
        ocultando.value = false;
    }, 500);
};

watchEffect(() => {
    const flash = page.props.flash;

    if (flash.success || flash.error || flash.warning) {
        if (flash.success) {
            flashMensaje.value = flash.success;
            tipo.value = 'success';
        } else if (flash.error) {
            flashMensaje.value = flash.error;
            tipo.value = 'error';
        } else if (flash.warning) {
            flashMensaje.value = flash.warning;
            tipo.value = 'warning';
        }

        visible.value = true;
        ocultando.value = false;

        if (temporizador) clearTimeout(temporizador);
        temporizador = setTimeout(() => cerrarMensaje(), 5000);
    }
});
</script>
<template>
    <div
        v-if="visible"
        class="fixed top-20 right-5 px-4 py-2 rounded shadow-lg z-50 flex items-center space-x-2 transition-all"
        :class="[
            tipo === 'success' ? 'bg-green-500 text-white' :
            tipo === 'error' ? 'bg-red-500 text-white' :
            'bg-yellow-400 text-black',
            ocultando ? 'animate-fade-out' : 'animate-fade-in'
        ]"
    >
        <span class="flex-1 whitespace-pre-line">{{ flashMensaje }}</span>
        <button
            @click="cerrarMensaje"
            class="text-xl font-bold hover:text-opacity-80 focus:outline-none"
        >
            x
        </button>
    </div>
</template>
