<script setup>
import {ref, onMounted, computed} from 'vue';
import {Head, router, usePage} from '@inertiajs/vue3';
import HeaderAdmin from "@/Components/HeaderAdmin.vue";
import FooterAdmin from "@/Components/FooterAdmin.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { route } from 'ziggy-js';
import { Link } from '@inertiajs/vue3';
import Swal from 'sweetalert2';
import AlertaFlash from "@/Components/AlertaFlash.vue";

const productos = ref([]);
const search = ref('');

onMounted(() => {
    productos.value = usePage().props.productos || [];
});

// Filtra los productos según la búsqueda
const productosFiltrados = computed(() => {
    if (!search.value.trim()) {
        return productos.value; // Si no hay búsqueda, mostrar todos
    }
    return productos.value.filter(producto => {
        return producto.nombre.toLowerCase().includes(search.value.toLowerCase()) ||
            producto.categoria.toLowerCase().includes(search.value.toLowerCase());
    });
});

function eliminarProducto(id) {
    Swal.fire({
        title: '¿Estás seguro?',
        text: "Esta acción no se puede deshacer",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Sí, eliminar',
        cancelButtonText: 'Cancelar',
    }).then((result) => {
        if (result.isConfirmed) {
            router.delete(route('admin.productos.destroy', id), {
                onSuccess: () => {
                    productos.value = productos.value.filter(p => p.id !== id);
                    Swal.fire(
                        'Eliminado',
                        'El producto ha sido eliminado.',
                        'success'
                    );
                },
                onError: () => {
                    Swal.fire(
                        'Error',
                        'Hubo un problema al eliminar el producto.',
                        'error'
                    );
                }
            });
        }
    });
}
</script>
<template>
    <Head title="Gestion Productos" />
    <div class="min-h-screen flex flex-col justify-between bg-sky-300 text-black">
        <AlertaFlash/>
        <HeaderAdmin/>
        <div class="flex">
            <Sidebar/>
            <!-- Main -->
            <main class="flex-1 p-6 overflow-x-auto">
                <!-- Formulario de búsqueda con evento input para actualización en tiempo real -->
                <form class="flex w-full max-w-sm mb-4 ml-auto">
                    <input
                        v-model="search"
                        type="text"
                        placeholder="Buscar productos..."
                        @input="buscarProducto"
                    class="w-full rounded-full px-4 py-2 border border-gray-300 focus:outline-none text-black"
                    />
                </form>

                <!-- Tabla de productos -->
                <table class="w-full border-4 border-blue-800 text-center">
                    <thead class="bg-blue-600 text-white text-xl">
                    <tr>
                        <th class="p-2">Código</th>
                        <th class="p-2">Nombre</th>
                        <th class="p-2">Precio</th>
                        <th class="p-2">Categoría</th>
                        <th class="p-2">Cantidad</th>
                        <th class="p-2">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="text-lg">
                    <tr
                        v-for="producto in productosFiltrados"
                        :key="producto.id"
                        class="odd:bg-sky-200 even:bg-sky-100 border-t-2 border-blue-800">
                        <td class="p-2">{{ producto.id }}</td>
                        <td class="p-2">{{ producto.nombre }}</td>
                        <td class="p-2">{{ parseFloat(producto.precio).toFixed(2) }} €</td>
                        <td class="p-2">{{ producto.categoria }}</td>
                        <td class="p-2">{{ producto.cantidad }}</td>
                        <td class="p-2 space-x-2">
                            <Link
                                :href="route('admin.productos.show', producto.id)"
                            >
                                <button class="bg-green-300 hover:bg-green-400 px-3 py-1 rounded">Ver</button>
                            </Link>

                            <Link
                                :href="route('admin.productos.edit', producto.id)"
                            >
                                <button class="bg-yellow-300 hover:bg-yellow-400 px-3 py-1 rounded">Editar</button>
                            </Link>
                            <button @click="eliminarProducto(producto.id)"
                                    class="bg-red-400 hover:bg-red-500 px-3 py-1 rounded">
                                Borrar
                            </button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </main>
        </div>
        <FooterAdmin/>
    </div>
</template>
