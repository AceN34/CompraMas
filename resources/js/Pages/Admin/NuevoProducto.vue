<script setup>
import {Head, useForm, usePage} from '@inertiajs/vue3'
import { ref, watch } from 'vue'
import InputError from '@/Components/InputError.vue'
import PrimaryButton from '@/Components/PrimaryButton.vue'

const page = usePage()
const categoriasExistentes = ref([...page.props.categorias, 'Otros'])

const form = useForm({
    nombre: '',
    precio: '',
    categoria: '',
    cantidad: '',
    imagen: null,
    errors: {},
})

const categoriaSeleccionada = ref('')

watch(categoriaSeleccionada, (value) => {
    form.categoria = value === 'Otros' ? '' : value
})

const submit = () => {
    form.post(route('admin.productos.store'), {
        onFinish: () => form.reset('imagen'),
    })
}
</script>

<template>
    <Head title="Nuevo Producto"/>
    <div class="min-h-screen bg-sky-300 flex flex-col items-center py-10 px-4 animate-fade-in">
        <h1 class="text-5xl font-extrabold mb-10 text-blue-900 drop-shadow">Añadir Producto</h1>

        <form
            @submit.prevent="submit"
            class="w-full max-w-2xl bg-white/80 backdrop-blur-md p-10 rounded-2xl shadow-xl space-y-8 text-black transition-all duration-300"
        >
            <!-- Nombre -->
            <div>
                <label for="nombre" class="block text-lg font-semibold text-blue-900 mb-1">Nombre del Producto</label>
                <input
                    id="nombre"
                    v-model="form.nombre"
                    type="text"
                    class="w-full px-5 py-3 rounded-full bg-blue-50 focus:ring-2 focus:ring-blue-400 focus:outline-none text-blue-900"
                    required
                />
                <InputError class="mt-1" :message="form.errors.nombre" />
            </div>

            <!-- Precio -->
            <div>
                <label for="precio" class="block text-lg font-semibold text-blue-900 mb-1">Precio (€)</label>
                <input
                    id="precio"
                    v-model="form.precio"
                    type="number"
                    step="0.01"
                    min="0"
                    class="w-full px-5 py-3 rounded-full bg-blue-50 focus:ring-2 focus:ring-blue-400 focus:outline-none text-blue-900"
                    required
                />
                <InputError class="mt-1" :message="form.errors.precio" />
            </div>

            <!-- Categoría -->
            <div>
                <label for="categoria" class="block text-lg font-semibold text-blue-900 mb-1">Categoría</label>
                <select
                    id="categoria"
                    v-model="categoriaSeleccionada"
                    class="w-full px-5 py-3 rounded-full bg-blue-50 focus:ring-2 focus:ring-blue-400 focus:outline-none text-blue-900"
                    required
                >
                    <option disabled value="">Selecciona una categoría</option>
                    <option v-for="cat in categoriasExistentes" :key="cat" :value="cat">{{ cat }}</option>
                </select>

                <transition name="fade">
                    <div v-if="categoriaSeleccionada === 'Otros'" class="mt-3">
                        <input
                            v-model="form.categoria"
                            type="text"
                            placeholder="Escribe una nueva categoría"
                            class="w-full px-5 py-3 rounded-full bg-blue-50 focus:ring-2 focus:ring-blue-400 focus:outline-none text-blue-900"
                            required
                        />
                    </div>
                </transition>

                <InputError class="mt-1" :message="form.errors.categoria" />
            </div>

            <!-- Cantidad -->
            <div>
                <label for="cantidad" class="block text-lg font-semibold text-blue-900 mb-1">Cantidad</label>
                <input
                    id="cantidad"
                    v-model="form.cantidad"
                    type="number"
                    min="0"
                    class="w-full px-5 py-3 rounded-full bg-blue-50 focus:ring-2 focus:ring-blue-400 focus:outline-none text-blue-900"
                    required
                />
                <InputError class="mt-1" :message="form.errors.cantidad" />
            </div>

            <!-- Imagen -->
            <div>
                <label for="imagen" class="block text-lg font-semibold text-blue-900 mb-1">Imagen del producto</label>
                <input
                    id="imagen"
                    type="file"
                    @change="e => form.imagen = e.target.files[0]"
                    class="w-full bg-white rounded-full px-4 py-2 border border-blue-300 file:bg-blue-600 file:text-white file:px-4 file:py-1 file:rounded-full"
                />
                <InputError class="mt-1" :message="form.errors.imagen" />
            </div>

            <!-- Botones -->
            <div class="flex justify-between items-center pt-6">
                <a
                    href="/admin/inicio"
                    class="bg-gray-300 hover:bg-gray-400 text-black font-semibold px-6 py-2 rounded-full transition"
                >
                    ← Volver
                </a>

                <PrimaryButton
                    class="bg-blue-700 hover:bg-blue-800 text-white text-lg font-semibold px-10 py-3 rounded-full"
                    :class="{ 'opacity-25': form.processing }"
                    :disabled="form.processing"
                >
                    Guardar Producto
                </PrimaryButton>
            </div>
        </form>
    </div>
</template>
