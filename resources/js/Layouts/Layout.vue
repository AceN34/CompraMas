<!-- resources/js/Layouts/Layout.vue -->
<script setup>
import { Link, usePage } from '@inertiajs/vue3'

const page = usePage()
</script>

<template>
    <div class="min-h-screen bg-gray-100 dark:bg-gray-900 font-sans antialiased">
        <!-- Puedes personalizar esta barra o incluirla como un componente aparte -->
        <nav class="bg-white dark:bg-gray-800 shadow">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4 flex justify-between">
                <Link href="/public" class="text-xl font-semibold text-gray-800 dark:text-white">
                    {{ page.props.appName ?? 'Laravel' }}
                </Link>
                <div>
                    <Link v-if="!page.props.auth.user" href="/login" class="text-blue-500">Login</Link>
                    <span v-else class="text-gray-700 dark:text-gray-300">Hola, {{ page.props.auth.user.name }}</span>
                </div>
            </div>
        </nav>

        <main>
            <slot />
        </main>
    </div>
</template>
