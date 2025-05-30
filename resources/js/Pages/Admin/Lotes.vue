<script setup>
import {ref, onMounted, computed} from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import HeaderAdmin from "@/Components/HeaderAdmin.vue";
import FooterAdmin from "@/Components/FooterAdmin.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { route } from 'ziggy-js';
import { Link } from '@inertiajs/vue3';
import Swal from "sweetalert2";
import AlertaFlash from "@/Components/AlertaFlash.vue";

const lotes = ref([]);

onMounted(() => {
    lotes.value = usePage().props.lotes || [];

    if (usePage().props.mustRefresh) {
        router.reload({ only: ['lotes'] });
    }
});

function eliminarLote(codigo) {
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
            router.delete(route('admin.lotes.destroy', codigo), {
                onSuccess: () => {
                    lotes.value = lotes.value.filter(l => l.codigo !== codigo);
                    Swal.fire(
                        'Eliminado',
                        'El lote ha sido eliminado.',
                        'success'
                    );
                },
                onError: () => {
                    Swal.fire(
                        'Error',
                        'Hubo un problema al eliminar el lote.',
                        'error'
                    );
                }
            });
        }
    });
}

const filtroProducto = ref('');

// Obtener lista de productos
const productosUnicos = computed(() => {
    const nombres = lotes.value.map(lote => lote.producto?.nombre || '');
    return [...new Set(nombres)].filter(n => n); // eliminar vacíos
});

const lotesFiltrados = computed(() => {
    if (!filtroProducto.value) {
        return lotes.value;
    }
    return lotes.value.filter(lote => lote.producto?.nombre === filtroProducto.value);
});
</script>

<template>
    <Head title="Gestión de Lotes" />
    <div class="min-h-screen flex flex-col justify-between bg-sky-300 text-black">
        <AlertaFlash/>
        <HeaderAdmin/>
        <div class="flex">
            <Sidebar/>
            <main class="flex-1 p-6 overflow-x-auto">
                <div class="flex justify-between items-center mb-6">
                    <h1 class="text-3xl font-bold text-blue-800">Gestión de Lotes</h1>
                    <!-- Filtro de lotes por producto -->
                    <div class="flex items-center gap-2">
                        <label for="producto" class="text-lg font-semibold text-blue-800">Filtrar por producto:</label>
                        <select id="producto" v-model="filtroProducto" class="p-2 rounded-lg border border-blue-400">
                            <option value="">Todos</option>
                            <option v-for="producto in productosUnicos" :key="producto" :value="producto">
                                {{ producto }}
                            </option>
                        </select>
                    </div>
                </div>
                <table class="w-full border-4 border-blue-800 text-center">
                    <thead class="bg-blue-600 text-white text-xl">
                    <tr>
                        <th class="p-2">Código</th>
                        <th class="p-2">Producto</th>
                        <th class="p-2">Cantidad</th>
                        <th class="p-2">Fecha de Caducidad</th>
                        <th class="p-2">Estado</th>
                        <th class="p-2">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="text-lg">
                    <tr v-if="lotes.length === 0">
                        <td colspan="6" class="p-4 text-center bg-sky-200 text-xl">
                            No hay lotes para mostrar.
                        </td>
                    </tr>
                    <tr
                        v-else
                        v-for="lote in lotesFiltrados"
                        :key="lote.codigo"
                        class="odd:bg-sky-200 even:bg-sky-100 border-t-2 border-blue-800">
                        <td class="p-2">{{ lote.codigo }}</td>
                        <td class="p-2">{{ lote.producto.nombre }}</td>
                        <td class="p-2">{{ lote.cantidad }}</td>
                        <td class="p-2">{{ lote.fecha_caducidad }}</td>
                        <td class="p-2">{{ lote.estado }}</td>
                        <td class="p-2 space-x-2">
                            <Link :href="route('admin.lotes.edit', lote.codigo)">
                                <button class="bg-yellow-300 hover:bg-yellow-400 px-3 py-1 rounded">Editar</button>
                            </Link>
                            <button @click="eliminarLote(lote.codigo)" class="bg-red-400 hover:bg-red-500 px-3 py-1 rounded">
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
