<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import {Head, Link, router, useForm} from '@inertiajs/vue3';
import Footer from "@/Components/Footer.vue";
import { route } from "ziggy-js";
import AlertaFlash from "@/Components/AlertaFlash.vue";

const form = useForm({
    nombre: '',
    email: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('register'));
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
                    />
                    <InputError class="mt-1 text-sm text-red-600" :message="form.errors.email" />
                </div>

                <div class="mb-4">
                    <div class="flex items-center gap-2 mb-1">
                        <label for="password" class="text-xl font-medium">Contraseña</label>
                        <div class="relative group">
                            <span class="text-white bg-blue-700 rounded-full w-5 h-5 flex items-center justify-center text-sm cursor-pointer hover:bg-blue-800">?</span>
                            <div class="absolute hidden group-hover:block bg-white text-black text-sm p-2 rounded-lg shadow-lg w-64 top-6 left-1/2 transform -translate-x-1/2 z-10">
                                La contraseña debe tener al menos 8 caracteres, incluir mayúsculas, minúsculas, números y símbolos.
                            </div>
                        </div>
                    </div>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        class="w-full px-4 py-2 rounded-full bg-blue-600 text-white placeholder-white text-lg focus:outline-none"
                        placeholder="Introduce tu contraseña"
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

