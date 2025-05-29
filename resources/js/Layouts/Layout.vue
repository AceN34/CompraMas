<script setup>
import Navigation from "@/Components/Header.vue";
import Footer from "@/Components/Footer.vue";
import AlertaFlash from "@/Components/AlertaFlash.vue";
</script>

<template>
    <div class="bg-sky-400">
        <AlertaFlash/>
        <Navigation />
        <main>
            <slot />
        </main>
        <Footer />
    </div>
</template>
