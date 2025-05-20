<script setup>
import { ref, onMounted } from 'vue';
import { Head, router, usePage } from '@inertiajs/vue3';
import HeaderAdmin from "@/Components/HeaderAdmin.vue";
import FooterAdmin from "@/Components/FooterAdmin.vue";
import Sidebar from "@/Components/Sidebar.vue";
import { route } from 'ziggy-js';
import { Link } from '@inertiajs/vue3';

const lotes = ref([]);

onMounted(() => {
    lotes.value = usePage().props.lotes || [];
});

function eliminarLote(id) {
    if (confirm('¿Estás seguro de que deseas eliminar este lote?')) {
        router.delete(route('admin.lotes.destroy', id), {
            onSuccess: () => {
                lotes.value = lotes.value.filter(l => l.id !== id);
            }
        });
    }
}
</script>

<template>
    <Head title="Gestión de Lotes" />
    <div class="min-h-screen flex flex-col justify-between bg-sky-300 text-black">
        <HeaderAdmin/>
        <div class="flex">
            <Sidebar/>
            <main class="flex-1 p-6 overflow-x-auto">
                <h1 class="text-3xl font-bold mb-6 text-blue-800">Gestión de Lotes</h1>
                <table class="w-full border-4 border-blue-800 text-center">
                    <thead class="bg-blue-600 text-white text-xl">
                    <tr>
                        <th class="p-2">ID</th>
                        <th class="p-2">Producto</th>
                        <th class="p-2">Cantidad</th>
                        <th class="p-2">Fecha de Entrada</th>
                        <th class="p-2">Acciones</th>
                    </tr>
                    </thead>
                    <tbody class="text-lg">
                    <tr
                        v-for="lote in lotes"
                        :key="lote.id"
                        class="odd:bg-sky-200 even:bg-sky-100 border-t-2 border-blue-800">
                        <td class="p-2">{{ lote.id }}</td>
                        <td class="p-2">{{ lote.producto?.nombre || 'Sin producto' }}</td>
                        <td class="p-2">{{ lote.cantidad }}</td>
                        <td class="p-2">{{ lote.fecha_entrada }}</td>
                        <td class="p-2 space-x-2">
                            <Link :href="route('admin.lotes.edit', lote.id)">
                                <button class="bg-yellow-300 hover:bg-yellow-400 px-3 py-1 rounded">Editar</button>
                            </Link>
                            <button @click="eliminarLote(lote.id)" class="bg-red-400 hover:bg-red-500 px-3 py-1 rounded">Borrar</button>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </main>
        </div>
        <FooterAdmin/>
    </div>
</template>
