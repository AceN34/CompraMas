<script setup>
import { usePage } from '@inertiajs/vue3';
import { computed } from 'vue';
import { useForm } from '@inertiajs/vue3';
import { route } from 'ziggy-js';

const page = usePage();
const admin = computed(() => page.props.auth?.admin);
const adminNombre = computed(() => admin.value?.nombre || 'Admin');

const form = useForm({});
</script>

<template>
    <header class="relative flex items-center justify-between px-10 py-6 bg-sky-500 border-b-4 border-blue-800">
        <a href="/admin/inicio">
            <img src="/images/logo.png" alt="Compra+" class="h-14" />
        </a>

        <h1 class="absolute left-1/2 transform -translate-x-1/2 text-5xl font-bold text-blue-900 tracking-wide">
            Gestión
        </h1>

        <div class="flex items-center gap-6 text-white">
            <span class="text-2xl font-semibold">{{ adminNombre }}</span>
            <button
                class="bg-black text-white text-lg px-6 py-2.5 rounded hover:bg-gray-800 transition"
                @click="form.post(route('admin.logout'))"
            >
                Cerrar Sesión
            </button>
        </div>
    </header>
</template>
