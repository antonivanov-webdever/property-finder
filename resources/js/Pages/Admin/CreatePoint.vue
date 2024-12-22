<script setup>
import {Link, useForm} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PointsForm from "@/Pages/Admin/Partials/PointsForm.vue";

const form = useForm({
    image: null,
    name: '',
    address: '',
    description: '',
    tg_link: '',
    youtube_link: '',
    category_id: 0,
    coordinates: null,
    is_visible: false,
});
</script>

<template>
    <AppLayout title="Create Point">
        <template #header>
            <div class="flex flex-row justify-between align-middle">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Создание новой точки
                </h2>
                <div>
                    <Link
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-3"
                        :href="route('points.index')"
                    >
                        Назад к списку
                    </Link>
                    <PrimaryButton
                        class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150"
                        @click="form.post(route('points.store'))"
                    >
                        Создать
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <div class="p-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <PointsForm :form="form" />
                </div>
            </div>
        </div>

    </AppLayout>
</template>
