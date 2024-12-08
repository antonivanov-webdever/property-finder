<script setup>
import { Head } from '@inertiajs/vue3';
import Map from "@/Pages/Shared/Partials/Map.vue";
</script>

<template>
    <Head title="Домашняя страница" />
    <body class="font-sans antialiased">
        <header class="header">
            <div class="header-container">
                <div class="logo">
                    <a href="/">
                        <img src="/admin/storage/images/logo.png" alt="">
                    </a>
                </div>
                <nav class="nav"></nav>
            </div>
        </header>
        <main class="main">
            <div class="py-2 sm:py-12">
                <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                    <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                        <Map />
                    </div>
                </div>
            </div>
        </main>
        <footer class="footer"></footer>
    </body>
</template>

<style scoped>

</style>
