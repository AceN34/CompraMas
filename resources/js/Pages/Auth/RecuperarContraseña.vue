<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import Footer from "@/Components/Footer.vue";
import {route} from "ziggy-js";

const props = defineProps({
    email: String,
});

const form = useForm({
    email: props.email || '',
});

const submit = () => {
    form.post(route('password.email'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Recuperar Contraseña" />

    <!-- Header -->
    <div class="bg-sky-400 py-4 flex justify-center border-b border-blue-800">
        <Link href="/">
            <img src="/images/logo.png" alt="Logo" class="h-12" />
        </Link>
    </div>

    <!-- Contenido principal -->
    <div class="bg-sky-200 flex flex-col flex-1">
        <div class="flex-1 flex items-center justify-center px-4 py-16">
            <div class="w-full max-w-md bg-sky-300 p-6 rounded-2xl border border-sky-500 shadow-md text-black my-16">
                <h1 class="text-4xl font-black mb-6 text-center">Recuperar Contraseña</h1>

                <form @submit.prevent="submit">
                    <div class="mb-4">
                        <label for="email" class="block text-xl font-medium mb-1">Correo Electrónico</label>
                        <input
                            id="email"
                            type="email"
                            v-model="form.email"
                            class="w-full px-4 py-2 rounded-full bg-blue-600 text-white placeholder-white text-lg focus:outline-none"
                            placeholder="Introduce tu correo"
                        />
                        <InputError class="mt-1 text-sm text-red-600" :message="form.errors.email" />
                    </div>

                    <div class="flex justify-center mb-6">
                        <PrimaryButton
                            class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-6 rounded-full"
                            :class="{ 'opacity-25': form.processing }"
                            :disabled="form.processing"
                        >
                            Enviar Enlace de Recuperación
                        </PrimaryButton>
                    </div>

                    <div class="text-center">
                        <Link href="/login" class="text-blue-600 hover:text-blue-800 text-lg font-semibold">
                            Volver al Login
                        </Link>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <Footer />
</template>
