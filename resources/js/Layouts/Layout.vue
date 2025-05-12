<!-- resources/js/Layouts/Layout.vue -->
<script setup>
import { Link, usePage } from '@inertiajs/vue3'
import Navigation from "@/Components/Navigation.vue";
import Footer from "@/Components/Footer.vue";

const page = usePage()
</script>

<template>
    <div>
        <Navigation />
        <main>
            <slot />
        </main>
        <Footer />
    </div>
</template>
