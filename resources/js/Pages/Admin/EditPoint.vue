<script setup>
import {Link, useForm, usePage} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import PointForm from "@/Pages/Admin/Partials/PointForm.vue";

const page = usePage();

const form = useForm({
    image: page.props.point.image,
    name: page.props.point.name,
    address: page.props.point.address,
    description: page.props.point.description,
    tg_link: page.props.point.tg_link,
    youtube_link: page.props.point.youtube_link,
    filter_id: page.props.point.filter_id,
    coordinates: JSON.parse(page.props.point.coordinates),
});
</script>

<template>
    <AppLayout title="Edit Point">
        <template #header>
            <div class="flex flex-row justify-between align-middle">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Редактирование точки
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
                        @click="form.post(route('points.update', {id: page.props.point.id}));"
                    >
                        Сохранить
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <div class="p-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <PointForm :form="form" />
                </div>
            </div>
        </div>

    </AppLayout>
</template>
