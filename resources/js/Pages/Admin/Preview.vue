<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import Welcome from '@/Components/Welcome.vue';
import MapPreview from "@/Pages/Admin/Partials/MapPreview.vue";
import FilterTabs from "@/Pages/Admin/Partials/FilterTabs.vue";
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
                    <div class="filters pt-4 px-6">
                        <h1 class="text-xl font-bold mb-5">Карта строек</h1>
                        <h3>Отображать объекты со статусом:</h3>
                        <FilterTabs />
                    </div>

                    <MapPreview />
                </div>
            </div>
        </div>
    </AppLayout>
</template>
