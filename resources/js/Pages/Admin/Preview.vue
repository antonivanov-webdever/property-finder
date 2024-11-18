<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Map from "@/Pages/Shared/Partials/Map.vue";
</script>

<template>
    <AppLayout title="Предпросмотр карты">
        <template #header>
            <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                Предпросмотр карты
            </h2>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <Map />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
