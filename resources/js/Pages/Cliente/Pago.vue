<script setup>
import { Head } from '@inertiajs/vue3';
import { route } from 'ziggy-js';
import Layout from '@/Layouts/Layout.vue';
import {onMounted} from "vue";

const props = defineProps({
    carrito: Array,
    subtotal: Number,
    envio: Object
});

const formatearPrecio = (precio) => {
    const numero = Number(precio)
    return isNaN(numero) ? '0,00€' : numero.toFixed(2).replace('.', ',') + '€'
};

defineOptions({ layout: Layout });

const CLIENT_ID = 'AWH7BoXTuLpwt-tGMF9AKk8m8MZiWL6oARqQ59XAt-lcoXf0lc9-OmWFPIFx8anEfy6p9CujyhZ6parj';

const loadPayPalScript = () => {
    return new Promise((resolve, reject) => {
        if (window.paypal) {
            resolve();
            return;
        }

        const script = document.createElement('script');
        script.src = `https://www.paypal.com/sdk/js?client-id=${CLIENT_ID}&currency=EUR`;
        script.onload = () => resolve();
        script.onerror = () => reject('Error al cargar el SDK de PayPal');
        document.head.appendChild(script);
    });
};

onMounted(async () => {
    try {
        await loadPayPalScript();

        window.paypal.Buttons({
            createOrder: (data, actions) => {
                return actions.order.create({
                    purchase_units: [{
                        amount: {
                            value: props.subtotal.toFixed(2)
                        }
                    }]
                });
            },
            onApprove: (data, actions) => {
                return actions.order.capture().then(details => {
                    window.location.href = route('venta.procesarPago', { orderId: data.orderID });
                });
            },
            onCancel: () => {
                window.location.href = route('venta.cancelarPago');
            },
            onError: (err) => {
                console.error('Error en PayPal', err);
                window.location.href = route('venta.error');
            }
        }).render('#paypal-button-container');

    } catch (e) {
        console.error('Error cargando PayPal', e);
    }
});
</script>

<template>
    <Head title="Pago" />
    <div class="flex flex-col min-h-screen">
        <div class="bg-sky-100 px-28 py-6 flex-1 flex flex-col">
            <div class="max-w-full flex flex-1 flex-col md:flex-row gap-6 text-black">
                <!-- Resumen del pedido -->
                <div class="md:w-2/3 flex flex-col flex-1 animate-fade-in">
                    <div class="bg-sky-200 rounded-lg p-6 shadow space-y-4">
                        <h2 class="text-2xl font-bold">Resumen del pedido</h2>

                        <div class="flex items-start justify-between">
                            <div>
                                <h3 class="text-lg font-semibold mb-2">Datos de envío</h3>
                                <p><strong>Nombre:</strong> {{ envio.nombre }}</p>
                                <p><strong>Dirección:</strong> {{ envio.direccion }}, {{ envio.ciudad }} ({{ envio.codigoPostal }})</p>
                                <p><strong>Teléfono:</strong> {{ envio.telefono }}</p>
                                <p><strong>Comentarios:</strong> {{ envio.comentarios || 'Ninguno' }}</p>
                            </div>
                            <div class="mt-4">
                                <a
                                    :href="route('venta.detalles')"
                                    class="inline-block bg-blue-500 hover:bg-blue-600 text-white font-semibold px-4 py-2 rounded transition"
                                >
                                    Modificar datos de envío
                                </a>
                            </div>
                        </div>


                        <div>
                            <h3 class="text-lg font-semibold mb-2">Productos</h3>
                            <div v-for="item in carrito" :key="item.id" class="flex items-center gap-4 bg-sky-300 rounded p-3 shadow">
                                <img
                                    :src="'/images/' + item.producto.imagen"
                                    alt="Imagen producto"
                                    class="w-20 h-20 object-contain"
                                />
                                <div class="flex-1">
                                    <h4 class="font-medium">{{ item.producto.nombre }}</h4>
                                    <p class="text-sm text-gray-600">Cantidad: {{ item.cantidad }}</p>
                                </div>
                                <div class="font-semibold">
                                    {{ formatearPrecio(item.producto.precio * item.cantidad) }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Total y botón de pago -->
                <div class="md:w-1/3 bg-sky-200 p-6 rounded-lg shadow-md space-y-4 self-start animate-fade-in">
                    <h3 class="text-xl font-semibold">Resumen de pago</h3>
                    <div class="flex justify-between">
                        <span>Subtotal</span>
                        <span class="font-semibold">{{ formatearPrecio(subtotal) }}</span>
                    </div>
                    <hr />
                    <h2 class="text-2xl font-bold mb-4">Pago</h2>
                    <div id="paypal-button-container" class="mt-6 bg-sky-200"></div>
                </div>
            </div>
        </div>
    </div>
</template>

