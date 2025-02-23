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
        <footer class="footer">
            <div class="footer-container">
                <div class="copyright"></div>
                <div class="ya-informer">
                    <!-- Yandex.Metrika informer -->
                    <a href="https://metrika.yandex.ru/stat/?id=100015090&amp;from=informer"
                       target="_blank" rel="nofollow"><img src="https://informer.yandex.ru/informer/100015090/3_1_FFFFFFFF_EFEFEFFF_0_pageviews"
                                                           style="width:88px; height:31px; border:0;" alt="Яндекс.Метрика" title="Яндекс.Метрика: данные за сегодня (просмотры, визиты и уникальные посетители)" class="ym-advanced-informer" data-cid="100015090" data-lang="ru" /></a>
                    <!-- /Yandex.Metrika informer -->
                </div>
            </div>
        </footer>
    </body>
</template>

<style scoped>

</style>
