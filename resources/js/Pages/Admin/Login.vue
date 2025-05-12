<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Footer from '@/Components/Footer.vue';
import { route } from 'ziggy-js';

defineProps({
    canResetPassword: {
        type: Boolean,
    },
    status: {
        type: String,
    },
});

const form = useForm({
    email: '',
    password: '',
    remember: false,
});

const submit = () => {
    form.post(route('admin.login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Iniciar Sesión - Admin" />
    <div class="bg-sky-200 flex flex-col text-black min-h-screen justify-between">
        <!-- Header con logo -->
        <div class="bg-sky-400 py-4 flex justify-center border-b border-blue-800">
            <Link href="/">
                <img src="/images/logo.png" alt="Logo" class="h-12" />
            </Link>
        </div>

        <!-- Formulario -->
        <div class="flex flex-col items-center justify-center mt-8">
            <h1 class="text-4xl font-black mb-6">Iniciar Sesión Empleado</h1>

            <form @submit.prevent="submit" class="w-full max-w-md px-4">
                <div class="mb-4">
                    <label for="email" class="block text-xl font-medium mb-1">Email</label>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        class="w-full px-4 py-2 rounded-full bg-blue-600 text-white placeholder-white text-lg focus:outline-none"
                        placeholder="Introduce tu correo"
                        required
                        autofocus
                        autocomplete="username"
                    />
                    <InputError class="mt-1 text-sm text-red-600" :message="form.errors.email" />
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-xl font-medium mb-1">Contraseña</label>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        class="w-full px-4 py-2 rounded-full bg-blue-600 text-white placeholder-white text-lg focus:outline-none"
                        placeholder="Introduce tu contraseña"
                        required
                        autocomplete="current-password"
                    />
                    <InputError class="mt-1 text-sm text-red-600" :message="form.errors.password" />
                </div>

                <div class="flex justify-center mb-6">
                    <PrimaryButton
                        class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-6 rounded-full"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Iniciar Sesión
                    </PrimaryButton>
                </div>

                <!-- Separador -->
                <div class="flex items-center my-6">
                    <hr class="flex-grow border-blue-600" />
                    <span class="px-4 text-xl font-bold text-gray-700">O</span>
                    <hr class="flex-grow border-blue-600" />
                </div>

                <!-- Acciones adicionales -->
                <div class="text-center mb-6">
                    <p class="text-lg font-medium">¿Eres cliente?</p>
                    <Link :href="route('login')" class="mt-2 inline-block bg-blue-700 hover:bg-blue-800 text-white py-2 px-6 rounded-lg font-semibold">
                        Login Cliente
                    </Link>
                </div>
            </form>
        </div>

        <Footer />
    </div>
</template>

