<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import Footer from "@/Components/Footer.vue";
import { route } from "ziggy-js";

const form = useForm({
    nombre: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'), {
        onFinish: () => router.visit('login'),
    });
};
</script>

<template>
    <Head title="Registro" />
    <div class="bg-sky-200 flex flex-col text-black">
        <div class="bg-sky-400 py-4 flex justify-center border-b border-blue-800">
            <Link href="/">
                <img src="/images/logo.png" alt="Logo" class="h-12" />
            </Link>
        </div>

        <div class="flex flex-col items-center justify-center mt-8">
            <h1 class="text-4xl font-black mb-6">Crear Cuenta</h1>

            <form @submit.prevent="submit" class="w-full max-w-md px-4">
                <div class="mb-4">
                    <label for="nombre" class="block text-xl font-medium mb-1">Nombre</label>
                    <input
                        id="nombre"
                        type="text"
                        v-model="form.nombre"
                        class="w-full px-4 py-2 rounded-full bg-blue-600 text-white placeholder-white text-lg focus:outline-none"
                        placeholder="Introduce tu nombre"
                        required
                    />
                    <InputError class="mt-1 text-sm text-red-600" :message="form.errors.nombre" />
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-xl font-medium mb-1">Email</label>
                    <input
                        id="email"
                        type="email"
                        v-model="form.email"
                        class="w-full px-4 py-2 rounded-full bg-blue-600 text-white placeholder-white text-lg focus:outline-none"
                        placeholder="Introduce tu correo"
                        required
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
                    />
                    <InputError class="mt-1 text-sm text-red-600" :message="form.errors.password" />
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-xl font-medium mb-1">Confirmar Contraseña</label>
                    <input
                        id="password_confirmation"
                        type="password"
                        v-model="form.password_confirmation"
                        class="w-full px-4 py-2 rounded-full bg-blue-600 text-white placeholder-white text-lg focus:outline-none"
                        placeholder="Repite tu contraseña"
                        required
                    />
                    <InputError class="mt-1 text-sm text-red-600" :message="form.errors.password_confirmation" />
                </div>

                <div class="flex justify-center mb-6">
                    <PrimaryButton
                        class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-6 rounded-full"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Registrarse
                    </PrimaryButton>
                </div>

                <div class="text-center mt-4 mb-5">
                    <p class="text-lg font-medium">¿Ya tienes cuenta?</p>
                    <Link href="/login" class="mt-2 inline-block bg-blue-700 hover:bg-blue-800 text-white py-2 px-6 rounded-lg font-semibold">
                        Iniciar Sesión
                    </Link>
                </div>
            </form>
        </div>
    </div>
    <Footer />
</template>

