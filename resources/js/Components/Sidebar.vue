<script setup>
import { computed } from 'vue'
import { Link, usePage } from '@inertiajs/vue3'
import { route } from 'ziggy-js'

const currentUrl = computed(() => usePage().url)

const openSection = computed(() => {
    if (currentUrl.value.startsWith('/admin/inicio')) return 'productos'
    if (currentUrl.value.startsWith('/admin/gestionLotes')) return 'lotes'
    if (currentUrl.value.startsWith('/admin/ventas')) return 'ventas'
    return null
})
</script>

<template>
    <aside class="w-1/6 bg-sky-400 text-left p-4 border-r-4 border-blue-800 min-h-screen">
        <nav class="space-y-6 text-black">

            <!-- Productos -->
            <div>
                <Link
                    :href="route('admin.inicio')"
                    class="text-2xl font-bold underline decoration-blue-800 w-full text-left px-2 py-1 rounded transition duration-200 hover:bg-blue-300 block"
                    :class="{ 'bg-blue-200 border-l-4 border-blue-700': openSection === 'productos' }"
                >
                    Productos
                </Link>

                <div v-if="openSection === 'productos'" class="mt-2 pl-4 space-y-2">
                    <Link
                        :href="route('admin.productos.nuevo')"
                        class="block font-semibold hover:bg-blue-200 px-2 py-1 rounded transition"
                    >
                        Nuevo Producto
                    </Link>
                </div>
            </div>

            <!-- Lotes -->
            <div>
                <Link
                    :href="route('admin.lotes.index')"
                    class="text-2xl font-bold underline decoration-blue-800 w-full text-left px-2 py-1 rounded transition duration-200 hover:bg-blue-300 block"
                    :class="{ 'bg-blue-200 border-l-4 border-blue-700': openSection === 'lotes' }"
                >
                    Lotes
                </Link>

                <div v-if="openSection === 'lotes'" class="mt-2 pl-4 space-y-2">
                    <Link
                        :href="route('admin.lotes.create')"
                        class="block font-semibold hover:bg-blue-200 px-2 py-1 rounded transition"
                    >
                        Nuevo Lote
                    </Link>
                </div>
            </div>

            <!-- Ventas -->
            <div>
                <Link
                    :href="route('admin.ventas.index')"
                    class="text-2xl font-bold underline decoration-blue-800 w-full text-left px-2 py-1 rounded transition duration-200 hover:bg-blue-300 block"
                    :class="{ 'bg-blue-200 border-l-4 border-blue-700': openSection === 'ventas' }"
                >
                    Ventas
                </Link>

                <div v-if="openSection === 'ventas'" class="mt-2 pl-4 space-y-2">
                    <Link
                        href="/admin/ventas/reporte"
                        class="block font-semibold hover:bg-blue-200 px-2 py-1 rounded transition"
                    >
                        Ver Reporte
                    </Link>
                </div>
            </div>
        </nav>
    </aside>
</template>
