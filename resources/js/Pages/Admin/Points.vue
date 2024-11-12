<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {onMounted, ref} from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PointsTableRow from "@/Pages/Admin/Partials/PointsTableRow.vue";
import {Link} from "@inertiajs/vue3";

const props = defineProps({
    points: {
        type: Array,
        required: false
    }
})

const points = ref([]);

onMounted(() => {
    points.value = props.points;
});

const changeVisibility = (id) => {
    const index = points.value.findIndex(point => point.id === id);
    points.value[index].isVisible = !points.value[index].isVisible;
}

const save = () => {
}

</script>

<template>
    <AppLayout title="Points">
        <template #header>
            <div class="flex flex-row justify-between align-middle">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Список точек
                </h2>
                <div>
                    <Link
                        class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150 mr-3"
                        :href="route('points.create')"
                    >
                        Добавить
                    </Link>
                    <SecondaryButton @click="save">Сохранить все</SecondaryButton>
                </div>
            </div>
        </template>

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <table class="table-fixed w-full">
                        <thead class="h-12 border-b-2">
                        <tr>
                            <th class="text-center px-2 w-10">Id</th>
                            <th class="text-center px-2 w-28">Фото</th>
                            <th class="text-left px-4">Название объекта</th>
                            <th class="text-center px-4 w-52">Фильтр</th>
                            <th class="text-center px-4 w-52">Дата изменения</th>
                            <th class="text-center px-2 w-24">Видимость</th>
                            <th class="text-center px-2 w-32">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <PointsTableRow
                            v-for="point in points"
                            :point="point"
                            :key="point.id"
                            @change-visibility="changeVisibility"
                        />
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </AppLayout>
</template>
