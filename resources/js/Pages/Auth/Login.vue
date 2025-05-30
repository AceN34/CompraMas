<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { Head, Link, useForm } from '@inertiajs/vue3';
import Footer from "@/Components/Footer.vue";
import {route} from "ziggy-js";
import AlertaFlash from "@/Components/AlertaFlash.vue";

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
});

const submit = () => {
    form.post(route('login'), {
        onFinish: () => form.reset('password'),
    });
};
</script>

<template>
    <Head title="Login" />
    <body class="min-h-screen">
        <AlertaFlash/>
        <div class="bg-sky-200 flex flex-col text-black min-h-screen flex-grow">

            <!-- Header -->
            <div class="bg-sky-400 py-4 flex justify-center border-b border-blue-800">
                <Link href="/">
                    <img src="/images/logo.png" alt="Logo" class="h-12" />
                </Link>
            </div>

            <!-- Formulario -->
            <div class="flex-grow flex items-center justify-center px-4">
                <div class="w-full max-w-md p-8">
                    <Head title="Iniciar Sesión" />

                    <h1 class="text-4xl font-black mb-6 text-center">Iniciar Sesión</h1>

                    <form @submit.prevent="submit" class="w-full">
                        <div class="mb-4">
                            <label for="email" class="block text-xl font-medium mb-1">Email</label>
                            <input
                                id="email"
                                type="email"
                                v-model="form.email"
                                class="w-full px-4 py-2 rounded-full bg-blue-600 text-white placeholder-white text-lg focus:outline-none"
                                placeholder="Introduce tu correo"
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

                        <div class="flex items-center my-6">
                            <hr class="flex-grow border-blue-600" />
                            <span class="px-4 text-xl font-bold text-gray-700">O</span>
                            <hr class="flex-grow border-blue-600" />
                        </div>

                        <!-- Opciones alternativas -->
                        <div class="flex justify-around mb-0">
                            <div class="text-center">
                                <p class="text-lg font-medium">¿No tienes cuenta?</p>
                                <Link href="/register" class="mt-2 inline-block bg-blue-700 hover:bg-blue-800 text-white py-2 px-6 rounded-lg font-semibold">
                                    Crear Cuenta
                                </Link>
                            </div>
                            <div class="text-center">
                                <p class="text-lg font-medium">¿Eres empleado?</p>
                                <Link :href="route('admin.login')" class="mt-2 inline-block bg-blue-700 hover:bg-blue-800 text-white py-2 px-6 rounded-lg font-semibold">
                                    Login Alternativo
                                </Link>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <Footer />
    </body>
</template>
