<script setup>
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { useForm, Head, Link } from '@inertiajs/vue3';
import Footer from "@/Components/Footer.vue";
import {route} from "ziggy-js";

const form = useForm({
    email: '',
    token: '',
    password: '',
    password_confirmation: '',
});

const submit = () => {
    form.post(route('password.update'), {
        onFinish: () => form.reset(),
    });
};
</script>

<template>
    <Head title="Restablecer Contraseña" />
    <div class="bg-sky-200 flex flex-col text-black">
        <div class="bg-sky-400 py-4 flex justify-center border-b border-blue-800">
            <Link href="/">
                <img src="/images/logo.png" alt="Logo" class="h-12" />
            </Link>
        </div>

        <div class="flex flex-col items-center justify-center mt-8">
            <h1 class="text-4xl font-black mb-6">Restablecer Contraseña</h1>

            <form @submit.prevent="submit" class="w-full max-w-md px-4">
                <div class="mb-4">
                    <label for="password" class="block text-xl font-medium mb-1">Nueva Contraseña</label>
                    <input
                        id="password"
                        type="password"
                        v-model="form.password"
                        class="w-full px-4 py-2 rounded-full bg-blue-600 text-white placeholder-white text-lg focus:outline-none"
                        placeholder="Nueva Contraseña"
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
                        placeholder="Confirmar Contraseña"
                    />
                    <InputError class="mt-1 text-sm text-red-600" :message="form.errors.password_confirmation" />
                </div>

                <div class="flex justify-center mb-6">
                    <PrimaryButton
                        class="bg-blue-700 hover:bg-blue-800 text-white font-semibold py-2 px-6 rounded-full"
                        :class="{ 'opacity-25': form.processing }"
                        :disabled="form.processing"
                    >
                        Restablecer Contraseña
                    </PrimaryButton>
                </div>
            </form>
        </div>
    </div>
    <Footer />
</template>
