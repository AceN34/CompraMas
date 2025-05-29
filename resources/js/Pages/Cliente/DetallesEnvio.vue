<script setup>
import { useForm } from '@inertiajs/vue3';
import Layout from "@/Layouts/Layout.vue";
import { route } from "ziggy-js";

// Recibir la propiedad "envio" desde el backend
const props = defineProps({
    envio: Object,  // Esta propiedad es pasada desde el backend
});

// Inicializamos el formulario con los valores de "envio" si existen
const form = useForm({
    nombre: props.envio?.nombre || '',  // Rellenar con datos existentes
    direccion: props.envio?.direccion || '',
    ciudad: props.envio?.ciudad || '',
    codigoPostal: props.envio?.codigoPostal || '',
    telefono: props.envio?.telefono || '',
    comentarios: props.envio?.comentarios || '',
});

// Función de envío
const enviar = () => {
    form.clearErrors();  // Limpiar los errores previos
    form.post(route('venta.confirmar'), {
        onError: (errors) => {
            // Capturar errores si los hay
            console.log(errors);
        }
    });
};

defineOptions({ layout: Layout });
</script>

<template>
    <div class="flex flex-col min-h-screen">
        <div class="bg-sky-100 px-28 py-6 flex-1 text-black">
            <div class="max-w-4xl mx-auto bg-sky-200 p-8 rounded-lg shadow-md animate-fade-in">
                <h2 class="text-2xl font-bold mb-6 text-center">Detalles del envío</h2>

                <form @submit.prevent="enviar" class="space-y-4">
                    <div>
                        <label class="block mb-1 font-medium">Nombre completo</label>
                        <input v-model="form.nombre" type="text"
                               class="w-full rounded px-4 py-2 border border-sky-300 bg-sky-100 focus:outline-none focus:ring"/>
                        <div v-if="form.errors.nombre" class="text-red-500 text-sm">{{ form.errors.nombre }}</div>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Dirección</label>
                        <input v-model="form.direccion" type="text"
                               class="w-full rounded px-4 py-2 border border-sky-300 bg-sky-100 focus:outline-none focus:ring"/>
                        <div v-if="form.errors.direccion" class="text-red-500 text-sm">{{ form.errors.direccion }}</div>
                    </div>

                    <div class="flex gap-4">
                        <div class="flex-1">
                            <label class="block mb-1 font-medium">Ciudad</label>
                            <input v-model="form.ciudad" type="text"
                                   class="w-full rounded px-4 py-2 border border-sky-300 bg-sky-100 focus:outline-none focus:ring"/>
                            <div v-if="form.errors.ciudad" class="text-red-500 text-sm">{{ form.errors.ciudad }}</div>
                        </div>

                        <div class="flex-1">
                            <label class="block mb-1 font-medium">Código postal</label>
                            <input v-model="form.codigoPostal" type="text"
                                   class="w-full rounded px-4 py-2 border border-sky-300 bg-sky-100 focus:outline-none focus:ring"/>
                            <div v-if="form.errors.codigoPostal" class="text-red-500 text-sm">
                                {{ form.errors.codigoPostal }}
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Teléfono</label>
                        <input v-model="form.telefono" type="text"
                               class="w-full rounded px-4 py-2 border border-sky-300 bg-sky-100 focus:outline-none focus:ring"/>
                        <div v-if="form.errors.telefono" class="text-red-500 text-sm">{{ form.errors.telefono }}</div>
                    </div>

                    <div>
                        <label class="block mb-1 font-medium">Comentarios (opcional)</label>
                        <textarea v-model="form.comentarios"
                                  class="w-full rounded px-4 py-2 border border-sky-300 bg-sky-100 focus:outline-none focus:ring"></textarea>
                    </div>

                    <button type="submit"
                            class="w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded transition">
                        Proceder al pago
                    </button>
                </form>
            </div>
        </div>
    </div>
</template>

