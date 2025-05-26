<script setup>
import { usePage, router } from '@inertiajs/vue3';
import { computed, onMounted, ref, reactive } from 'vue';
import Footer from '@/Components/Footer.vue';
import Header from '@/Components/Header.vue';
import SidebarPerfil from '@/Components/SidebarPerfil.vue';

const page = usePage();
const cliente = computed(() => page.props.auth.cliente);
const errores = computed(() => page.props.errors);

// Mostrar formulario
const modo = ref(null); // 'editarPerfil' | 'cambiarContrasena' | null

const irAEditarPerfil = () => {
    form.nombre = cliente.value?.nombre ?? '';
    form.email = cliente.value?.email ?? '';
    modo.value = 'editarPerfil';
};

const irACambiarContrasena = () => {
    form.password_actual = '';
    form.password = '';
    form.password_confirmation = '';
    modo.value = 'cambiarContrasena';
};

const cancelar = () => {
    modo.value = null;
};

const form = reactive({
    nombre: '',
    email: '',
    password_actual: '',
    password: '',
    password_confirmation: '',
});

const actualizarPerfil = () => {
    router.put('/perfil/editar', {
        nombre: form.nombre,
        email: form.email,
    }, {
        onSuccess: () => {
            modo.value = null;
            flashMensaje.value = page.props.flash.success; // Para mostrar notificación al actualizar
            setTimeout(() => cerrarMensaje(), 5000);
        },
    });
};

const cambiarContrasena = () => {
    router.put('/perfil/cambiar-contrasena', {
        password_actual: form.password_actual,
        password: form.password,
        password_confirmation: form.password_confirmation,
    }, {
        onSuccess: () => {
            modo.value = null;
            flashMensaje.value = page.props.flash.success; // Para mostrar notificación al cambiar contraseña
            setTimeout(() => cerrarMensaje(), 5000);
        },
    });
};


const flashMensaje = ref(null);
const ocultar = ref(false);

const cerrarMensaje = () => {
    ocultar.value = true;
    setTimeout(() => {
        flashMensaje.value = null;
        ocultar.value = false;
    }, 400); // Tiene que coincidir con el tiempo del fade-out
};

onMounted(() => {
    if (page.props.flash?.success) {
        flashMensaje.value = page.props.flash.success;
        setTimeout(() => cerrarMensaje(), 5000);
    }
});
</script>

<template>
    <div class="min-h-screen bg-sky-100 flex flex-col">
        <Header />
        <div v-if="flashMensaje"
             :class="['fixed top-5 right-5 bg-green-500 text-white px-4 py-2 rounded shadow-lg z-50 flex items-center space-x-2 transition-opacity',
             ocultar ? 'animate-fade-out' : 'animate-fade-in']"
        >
            <span>{{ flashMensaje }}</span>
            <button
                @click="cerrarMensaje"
                class="text-white text-xl leading-none hover:text-gray-200"
                aria-label="Cerrar"
            >
                &times;
            </button>
        </div>
        <div class="flex flex-1">
            <SidebarPerfil />

            <main class="flex-grow px-6 py-10">
                <div class="bg-white rounded-lg shadow-lg p-8 border-l-8 border-blue-600 w-full max-w-5xl mx-auto animate-fade-in">
                    <template v-if="modo === null">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-10">
                            <!-- Datos del cliente -->
                            <div>
                                <h2 class="text-2xl font-bold text-sky-600 mb-6">Datos de la cuenta</h2>

                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium">Nombre:</label>
                                    <p class="text-lg text-gray-900">{{ cliente?.nombre }}</p>
                                </div>

                                <div class="mb-4">
                                    <label class="block text-gray-700 font-medium">Email:</label>
                                    <p class="text-lg text-gray-900">{{ cliente?.email }}</p>
                                </div>

                                <div class="mb-6">
                                    <label class="block text-gray-700 font-medium">Fecha de registro:</label>
                                    <p class="text-lg text-gray-900">
                                        {{ new Date(cliente?.created_at).toLocaleDateString() }}
                                    </p>
                                </div>

                                <div class="flex flex-col sm:flex-row gap-4 mt-6">
                                    <button @click="irAEditarPerfil"
                                            class="bg-sky-600 hover:bg-sky-700 text-white font-semibold py-2 px-4 rounded-lg transition w-full sm:w-auto">
                                        Editar Perfil
                                    </button>

                                    <button @click="irACambiarContrasena"
                                            class="bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2 px-4 rounded-lg transition w-full sm:w-auto">
                                        Cambiar Contraseña
                                    </button>
                                </div>
                            </div>

                            <!-- Información adicional -->
                            <div class="space-y-6 flex flex-col justify-center">
                                <div class="bg-sky-100 p-6 rounded-xl text-center shadow">
                                    <p class="text-sm text-gray-600">Pedidos realizados</p>
                                    <p class="text-3xl font-bold text-sky-700">12</p>
                                </div>
                                <div class="bg-green-100 p-6 rounded-xl text-center shadow">
                                    <p class="text-sm text-gray-600">Estado de cuenta</p>
                                    <p class="text-3xl font-bold text-green-700">Verificado</p>
                                </div>
                            </div>
                        </div>
                    </template>

                    <!-- Formulario Editar Perfil -->
                    <template v-else-if="modo === 'editarPerfil'">
                        <h2 class="text-2xl font-bold text-sky-600 mb-6">Editar Perfil</h2>
                        <form @submit.prevent="actualizarPerfil" class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-medium">Nombre</label>
                                <input v-model="form.nombre" class="w-full rounded-lg text-black border-gray-300 focus:ring focus:ring-sky-200" />
                                <p v-if="errores.nombre" class="text-red-500 text-sm mt-1">{{ errores.nombre }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium">Email</label>
                                <input v-model="form.email" class="w-full rounded-lg text-black border-gray-300 focus:ring focus:ring-sky-200" />
                                <p v-if="errores.email" class="text-red-500 text-sm mt-1">{{ errores.email }}</p>
                            </div>
                            <div class="flex gap-4 mt-6">
                                <button type="submit" class="bg-sky-600 text-white py-2 px-4 rounded-lg hover:bg-sky-700">
                                    Guardar Cambios
                                </button>
                                <button type="button" @click="cancelar" class="text-gray-600 hover:underline">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </template>

                    <!-- Formulario Cambiar Contraseña -->
                    <template v-else-if="modo === 'cambiarContrasena'">
                        <h2 class="text-2xl font-bold text-blue-600 mb-6">Cambiar Contraseña</h2>
                        <form @submit.prevent="cambiarContrasena" class="space-y-4">
                            <div>
                                <label class="block text-gray-700 font-medium">Contraseña Actual</label>
                                <input type="password" v-model="form.password_actual" class="w-full rounded-lg text-black border-gray-300 focus:ring focus:ring-blue-200" />
                                <p v-if="errores.password_actual" class="text-red-500 text-sm mt-1">{{ errores.password_actual }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium">Nueva Contraseña</label>
                                <input type="password" v-model="form.password" class="w-full rounded-lg text-black border-gray-300 focus:ring focus:ring-blue-200" />
                                <p v-if="errores.password" class="text-red-500 text-sm mt-1">{{ errores.password }}</p>
                            </div>
                            <div>
                                <label class="block text-gray-700 font-medium">Confirmar Contraseña</label>
                                <input type="password" v-model="form.password_confirmation" class="w-full rounded-lg text-black border-gray-300 focus:ring focus:ring-blue-200" />
                                <p v-if="errores.password_confirmation" class="text-red-500 text-sm mt-1">{{ errores.password_confirmation }}</p>
                            </div>
                            <div class="flex gap-4 mt-6">
                                <button type="submit" class="bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700">
                                    Cambiar
                                </button>
                                <button type="button" @click="cancelar" class="text-gray-600 hover:underline">
                                    Cancelar
                                </button>
                            </div>
                        </form>
                    </template>
                </div>
            </main>
        </div>
        <Footer />
    </div>
</template>
