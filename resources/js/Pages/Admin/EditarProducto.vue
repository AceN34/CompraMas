<script setup>
import { ref, watch } from 'vue'
import { useForm } from '@inertiajs/vue3'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'
import { route } from 'ziggy-js'

const props = defineProps({
    producto: Object,
    categorias: Array,
})

const categoriasExistentes = ref([...props.categorias, 'Otros'])
const nuevaCategoria = ref('')

const form = useForm({
    nombre: props.producto.nombre,
    precio: props.producto.precio,
    categoria: props.producto.categoria,
    cantidad: props.producto.cantidad,
    imagen: null,
})

// Se asegura que cuando el valor de 'form.categoria' sea 'Otros', el valor de 'nuevaCategoria' se mantenga
watch(form.categoria, (val) => {
    if (val !== 'Otros' && nuevaCategoria.value) {
        // Si no se selecciona 'Otros', limpiamos el valor de nuevaCategoria
        nuevaCategoria.value = ''
    }
})

const submit = () => {
    // Si la categoría es 'Otros', se usa el valor de nuevaCategoria
    const categoriaFinal = form.categoria === 'Otros' ? nuevaCategoria.value : form.categoria

    form.transform((data) => {
        // Si no se ha seleccionado una nueva imagen, no se modifica la imagen
        if (!data.imagen) {
            const { imagen, ...rest } = data
            return {
                ...rest,
                categoria: categoriaFinal, // Se asigna la categoría final
                _method: 'put',
            }
        }

        // Si hay imagen seleccionada, se envia
        return {
            ...data,
            categoria: categoriaFinal, // Asignamos la categoría final
            _method: 'put',
        }
    }).post(route('admin.productos.update', props.producto.id), {
        forceFormData: true,
        preserveScroll: true,

        onError: (errors) => {
            console.error("Errores de validación:", errors)
        },
    })
}
</script>

<template>
    <div class="min-h-screen bg-sky-300 flex flex-col items-center py-10 px-4 animate-fade-in">
        <h1 class="text-4xl font-bold mb-10 text-blue-900">Editar Producto</h1>

        <form v-if="form" @submit.prevent="submit" class="w-full max-w-xl bg-white p-8 rounded-lg shadow-md space-y-6 text-black">
            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-lg font-semibold text-blue-800 mb-1">Nombre</label>
                <input
                    id="nombre"
                    v-model="form.nombre"
                    type="text"
                    class="w-full px-4 py-2 rounded-full bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <InputError class="mt-1 text-sm text-red-600" :message="form.errors.nombre" />
            </div>

            <!-- Precio -->
            <div>
                <label for="precio" class="block text-lg font-semibold text-blue-800 mb-1">Precio (€)</label>
                <input
                    id="precio"
                    v-model="form.precio"
                    type="number"
                    step="0.01"
                    class="w-full px-4 py-2 rounded-full bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <InputError class="mt-1 text-sm text-red-600" :message="form.errors.precio" />
            </div>

            <!-- Categoría -->
            <div>
                <label for="categoria" class="block text-lg font-semibold text-blue-800 mb-1">Categoría</label>
                <select
                    id="categoria"
                    v-model="form.categoria"
                    class="w-full px-4 py-2 rounded-full bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500 mb-2"
                >
                    <option disabled value="">Selecciona una categoría</option>
                    <option v-for="cat in categoriasExistentes" :key="cat" :value="cat">{{ cat }}</option>
                </select>

                <!-- Input para nueva categoría si se selecciona 'Otros' -->
                <div v-if="form.categoria === 'Otros'">
                    <input
                        v-model="nuevaCategoria"
                        type="text"
                        placeholder="Escribe una nueva categoría"
                        class="w-full px-4 py-2 rounded-full bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                    />
                </div>
                <InputError class="mt-1 text-sm text-red-600" :message="form.errors.categoria" />
            </div>

            <!-- Cantidad -->
            <div>
                <label for="cantidad" class="block text-lg font-semibold text-blue-800 mb-1">Cantidad</label>
                <input
                    id="cantidad"
                    v-model="form.cantidad"
                    type="number"
                    min="0"
                    class="w-full px-4 py-2 rounded-full bg-blue-100 focus:outline-none focus:ring-2 focus:ring-blue-500"
                />
                <InputError class="mt-1 text-sm text-red-600" :message="form.errors.cantidad" />
            </div>

            <!-- Imagen actual -->
            <div v-if="props.producto.imagen">
                <label class="block text-lg font-semibold text-blue-800 mb-2">Imagen actual:</label>
                <img :src="'/images/' + props.producto.imagen" :alt="props.producto.nombre" class="max-h-40 rounded shadow mb-4" />
            </div>

            <!-- Cambiar imagen -->
            <div>
                <label for="imagen" class="block text-lg font-semibold text-blue-800 mb-1">Cambiar imagen</label>
                <input
                    id="imagen"
                    type="file"
                    accept="image/*"
                    @change="e => form.imagen = e.target.files[0]"
                    class="w-full bg-white rounded-full px-4 py-2 border border-blue-300 file:bg-blue-600 file:text-white file:px-4 file:py-1 file:rounded-full"
                />
                <InputError class="mt-1 text-sm text-red-600" :message="form.errors.imagen" />
            </div>

            <!-- Botones -->
            <div class="flex justify-between items-center pt-4">
                <a
                    href="/admin/inicio"
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
