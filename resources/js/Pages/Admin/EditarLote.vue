<script setup>
import {Head, useForm} from '@inertiajs/vue3';
import { ref, onMounted, watch } from 'vue';
import InputError from '@/Components/InputError.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import { route } from "ziggy-js";

const props = defineProps({
    lote: Object,
    productos: Array,
});

// Formulario reactivo
const form = useForm({
    codigo: props.lote.codigo,
    producto_id: props.lote.producto_id,
    cantidad: props.lote.cantidad,
    fecha_caducidad: props.lote.fecha_caducidad,
    estado: props.lote.estado,
});

const productoSeleccionado = ref(
    props.productos.find(p => p.id === props.lote.producto_id)
);

const esCaducado = ref(false);

const maxCantidad = ref(0);

function calcularMaxCantidad() {
    const producto = productoSeleccionado.value;

    if (!producto || !producto.lotes || !Array.isArray(producto.lotes)) {
        maxCantidad.value = producto?.cantidad ?? 0;
        return;
    }

    const totalAsignado = producto.lotes
        .filter(l => l.codigo !== form.codigo)
        .reduce((acc, l) => acc + l.cantidad, 0);

    maxCantidad.value = producto.cantidad - totalAsignado;

}

// Para que salga en formato yyyy-mm-dd
function parseFecha(fechaStr) {
    if (fechaStr.includes('/')) {
        const [dia, mes, año] = fechaStr.split('/');
        return `${año}-${mes}-${dia}`;
    }
    return fechaStr;
}

function verificarFechaCaducidad(fecha = form.fecha_caducidad) {
    if (!fecha) return;

    const fechaFormateada = parseFecha(fecha);
    const fechaIngresada = new Date(fechaFormateada + 'T00:00:00');
    const hoy = new Date();
    hoy.setHours(0, 0, 0, 0);

    const estaCaducado = fechaIngresada.getTime() < hoy.getTime();
    esCaducado.value = estaCaducado;
}

watch(() => form.fecha_caducidad, (nuevaFecha) => {
    verificarFechaCaducidad(nuevaFecha);
});

watch(productoSeleccionado, () => {
    calcularMaxCantidad();
});

onMounted(() => {
    calcularMaxCantidad();
    verificarFechaCaducidad();
});

function submit() {
    form.put(route('admin.lotes.update', props.lote.codigo));
}
</script>
<template>
    <Head title="Editar Lote"/>
    <div class="min-h-screen bg-sky-300 flex flex-col items-center py-10 px-4 animate-fade-in">
        <h1 class="text-4xl font-bold mb-10 text-blue-900 drop-shadow">Editar Lote</h1>

        <form @submit.prevent="submit" class="w-full max-w-xl bg-white p-8 rounded-lg shadow-md space-y-6 text-black">
            <!-- Código (solo lectura) -->
            <div>
                <label for="codigo" class="block text-lg font-semibold text-blue-900 mb-1">Código</label>
                <input
                    id="codigo"
                    v-model="form.codigo"
                    type="text"
                    disabled
                    class="w-full px-5 py-3 rounded-full bg-gray-200 text-gray-700 cursor-not-allowed"
                />
            </div>

            <!-- Producto (no modificable pero visible) -->
            <div>
                <label class="block text-lg font-semibold text-blue-900 mb-1">Producto</label>
                <input
                    type="text"
                    :value="productoSeleccionado?.nombre || 'No encontrado'"
                    disabled
                    class="w-full px-5 py-3 rounded-full bg-gray-200 text-gray-700 cursor-not-allowed"
                />
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
                    :max="maxCantidad"
                    min="1"
                />
                <small v-if="productoSeleccionado" class="text-sm text-gray-600">
                    Máx: {{ maxCantidad }} unidades
                </small>
                <InputError class="mt-1" :message="form.errors.cantidad" />
            </div>

            <!-- Fecha de caducidad -->
            <div>
                <label for="fecha_caducidad" class="block text-lg font-semibold text-blue-900 mb-1">Fecha de caducidad</label>
                <input
                    id="fecha_caducidad"
                    v-model="form.fecha_caducidad"
                    type="date"
                    class="w-full px-5 py-3 rounded-full bg-blue-50 focus:ring-2 focus:ring-blue-400 text-blue-900"
                />
                <small v-if="form.fecha_caducidad" :class="esCaducado ? 'text-red-600' : 'text-green-600'" class="text-sm mt-1">
                    {{ esCaducado ? 'Este lote estará caducado' : 'Este lote estará consumible' }}
                </small>
                <InputError class="mt-1" :message="form.errors.fecha_caducidad" />
            </div>

            <!-- Estado (solo visible, no modificable) -->
            <div>
                <label class="block text-lg font-semibold text-blue-900 mb-1">Estado Actual</label>
                <input
                    type="text"
                    v-model="form.estado"
                    disabled
                    class="w-full px-5 py-3 rounded-full bg-gray-200 text-gray-700 cursor-not-allowed"
                />
            </div>

            <!-- Botones -->
            <div class="flex justify-between items-center pt-4">
                <a
                    href="/admin/gestionLotes"
                    class="bg-gray-300 hover:bg-gray-400 text-black font-semibold px-6 py-2 rounded-full transition"
                >
                    ← Volver
                </a>

                <PrimaryButton
                    class="bg-blue-700 hover:bg-blue-800 text-white text-lg font-semibold px-14 py-3 rounded-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Guardar Cambios
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
