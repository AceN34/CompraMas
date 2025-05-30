<script setup>
import { ref, watch, computed } from 'vue'
import {Head, useForm, usePage} from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import {route} from "ziggy-js";

const productos = usePage().props.productos
const productoSeleccionado = ref(null)

const form = useForm({
    codigo: '',
    producto_id: '',
    cantidad: '',
    fecha_caducidad: '',
    errors: {}
})

// Actualizar producto seleccionado cuando cambia el ID
watch(() => form.producto_id, (nuevoId) => {
    productoSeleccionado.value = productos.find(p => p.id == nuevoId)
})

// Calcular cantidad máxima permitida
const maxCantidad = computed(() => {
    return productoSeleccionado.value ? productoSeleccionado.value.disponible : 0
})

const submit = () => {
    form.post(route('admin.lotes.store'))
}
</script>


<template>
    <Head title="Nuevo Lote" />
    <div class="min-h-screen bg-sky-300 flex flex-col items-center py-10 px-4 animate-fade-in">
        <h1 class="text-5xl font-extrabold mb-10 text-blue-900 drop-shadow">Añadir Lote</h1>

        <form
            @submit.prevent="submit"
            class="w-full max-w-xl bg-white/80 backdrop-blur-md p-10 rounded-2xl shadow-xl space-y-8 text-black"
        >
            <!-- Código -->
            <div>
                <label for="codigo" class="block text-lg font-semibold text-blue-900 mb-1">Código del Lote</label>
                <input
                    id="codigo"
                    v-model="form.codigo"
                    type="text"
                    class="w-full px-5 py-3 rounded-full bg-blue-50 focus:ring-2 focus:ring-blue-400 text-blue-900"
                    placeholder="Ej: 010425"
                />
                <InputError class="mt-1" :message="form.errors.codigo" />
            </div>

            <!-- Producto -->
            <div>
                <label for="producto_id" class="block text-lg font-semibold text-blue-900 mb-1">Producto</label>
                <select
                    id="producto_id"
                    v-model="form.producto_id"
                    class="w-full px-5 py-3 rounded-full bg-blue-50 focus:ring-2 focus:ring-blue-400 text-blue-900"
                >
                    <option disabled value="">Selecciona un producto</option>
                    <option
                        v-for="p in productos"
                        :key="p.id"
                        :value="p.id"
                        :disabled="p.disponible <= 0"
                    >
                        {{ p.nombre }} (Sin Lote: {{ p.disponible }})
                    </option>
                </select>
                <InputError class="mt-1" :message="form.errors.producto_id" />
            </div>

            <!-- Cantidad -->
            <div>
                <label for="cantidad" class="block text-lg font-semibold text-blue-900 mb-1">Cantidad</label>
                <input
                    id="cantidad"
                    v-model="form.cantidad"
                    type="number"
                    class="w-full px-5 py-3 rounded-full bg-blue-50 focus:ring-2 focus:ring-blue-400 text-blue-900"
                    :disabled="!productoSeleccionado"
                />
                <small v-if="productoSeleccionado" class="text-sm text-gray-600">
                    Máx: {{ maxCantidad }} unidades
                </small>
                <InputError class="mt-1" :message="form.errors.cantidad" />
            </div>

            <!-- Fecha de Caducidad -->
            <div>
                <label for="fecha_caducidad" class="block text-lg font-semibold text-blue-900 mb-1">Fecha de Caducidad</label>
                <input
                    id="fecha_caducidad"
                    v-model="form.fecha_caducidad"
                    type="date"
                    class="w-full px-5 py-3 rounded-full bg-blue-50 focus:ring-2 focus:ring-blue-400 text-blue-900"
                />
                <InputError class="mt-1" :message="form.errors.fecha_caducidad" />
            </div>

            <!-- Botones -->
            <div class="flex justify-between items-center pt-6">
                <a
                    href="/admin/gestionLotes"
                    class="bg-gray-300 hover:bg-gray-400 text-black font-semibold px-6 py-2 rounded-full transition"
                >
                    ← Volver
                </a>

                <PrimaryButton
                    class="bg-blue-700 hover:bg-blue-800 text-white text-lg font-semibold px-10 py-3 rounded-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Guardar Lote
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
