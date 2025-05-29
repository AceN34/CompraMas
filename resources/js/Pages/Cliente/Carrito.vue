<script setup>
import { computed } from 'vue';
import { Head, router, Link } from '@inertiajs/vue3';
import { route } from "ziggy-js";
import Layout from "@/Layouts/Layout.vue";

const props = defineProps({
    carrito: Array,
    subtotal: Number,
    flash: Object,
});

const total = computed(() => props.subtotal);

const formatearPrecio = (precio) => {
    const numero = Number(precio)
    return isNaN(numero) ? '0,00€' : numero.toFixed(2).replace('.', ',') + '€'
};

const actualizarCantidad = (item) => {
    router.put(route('carrito.actualizar', item.id), {
        cantidad: item.cantidad
    }, {
        preserveScroll: true,
        only: ['carrito', 'subtotal', 'totalCarrito', 'flash']
    });
};

const eliminarProducto = (id) => {
    router.delete(route('carrito.eliminar', id), {
        preserveScroll: true,
        only: ['carrito', 'subtotal', 'totalCarrito', 'flash']
    });
};

const irADetalles = () => {
    router.visit(route('venta.detalles'));
};

const vaciarCarrito = () => {
    router.delete(route('carrito.vaciar'), {
        preserveScroll: true,
        only: ['carrito', 'subtotal', 'totalCarrito', 'flash']
    });
};

defineOptions({ layout: Layout });
</script>
<template>
    <Head title="Carrito" />
    <div class="flex flex-col min-h-screen">
        <div class="bg-sky-100 px-28 py-6 flex-1 flex flex-col">
            <div class="max-w-full flex flex-1 flex-col md:flex-row gap-6 text-black">
                <!-- Lista de productos -->
                <div class="md:w-2/3 flex flex-col flex-1 animate-fade-in">
                    <!-- Botón Vaciar Carrito -->
                    <button
                        v-if="props.carrito.length > 0"
                        @click="vaciarCarrito"
                        class="self-end mb-4 px-4 py-2 bg-red-500 hover:bg-red-600 text-white font-semibold rounded transition"
                    >
                        Vaciar carrito
                    </button>
                    <div class="flex-1 flex flex-col space-y-4">
                        <!-- Si no hay productos en el carrito -->
                        <div v-if="props.carrito.length === 0" class="flex items-center justify-center flex-1 bg-sky-200 rounded-lg shadow text-gray-700">
                        No hay productos disponibles en el carrito.
                        </div>
                        <!-- Si hay productos en el carrito -->
                        <div
                            v-else
                            v-for="item in props.carrito"
                            :key="item.id"
                            class="flex bg-sky-200 rounded-lg overflow-hidden shadow hover:shadow-lg"
                        >
                            <Link
                                :href="route('productos.detalles', item.producto.id)"
                            >
                            <img
                                :src="'/images/' + item.producto.imagen"
                                alt="Imagen producto"
                                class="w-36 h-36 object-contain rounded border shadow"
                            />
                            </Link>
                            <div class="flex-1 p-4">
                                <Link
                                    :href="route('productos.detalles', item.producto.id)"
                                >
                                <h2 class="font-semibold text-lg hover:text-sky-600">{{ item.producto.nombre }}</h2>
                                </Link>
                                <div class="flex items-center gap-2 mt-2">
                                    <label class="text-sm">Cantidad:</label>
                                    <input
                                        type="number"
                                        v-model.number="item.cantidad"
                                        @change="actualizarCantidad(item)"
                                        min="1"
                                        class="w-16 px-2 py-1 bg-sky-100 rounded text-center border border-sky-300 focus:outline-none focus:ring"
                                    />
                                </div>
                                <p class="mt-2 font-semibold">{{ formatearPrecio(item.producto.precio) }}</p>
                            </div>
                            <button
                                @click="eliminarProducto(item.id)"
                                class="text-2xl text-red-500 hover:text-red-700 px-8"
                                title="Eliminar"
                            >
                                x
                            </button>
                        </div>
                    </div>
                </div>

                <!-- Resumen -->
                <div v-if="!(props.carrito.length === 0)"
                    class="md:w-1/3 bg-sky-200 p-6 rounded-lg shadow-md space-y-3 self-start animate-fade-in">
                    <div
                        v-for="item in carrito"
                        :key="item.id + '-resumen'"
                        class="flex justify-between"
                    >
                        <span>{{ item.producto.nombre }}</span>
                        <span>{{ formatearPrecio(item.producto.precio * item.cantidad) }}</span>
                    </div>
                    <hr />
                    <div class="flex justify-between font-semibold text-lg">
                        <span>Subtotal</span>
                        <span>{{ formatearPrecio(total) }}</span>
                    </div>
                    <button
                        @click="irADetalles"
                        class="mt-4 w-full bg-blue-500 hover:bg-blue-600 text-white font-semibold py-2 rounded transition"
                    >
                        Continuar con el pedido
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>
