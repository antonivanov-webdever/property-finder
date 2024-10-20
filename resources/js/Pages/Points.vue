<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {reactive, ref} from "vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PointsTableRow from "@/Pages/Admin/Points/PointsTableRow.vue";

const points = reactive([
    {
        id: 1,
        image: 'img.webp',
        name: 'Ferrari Park Tower',
        updatedAt: '2024-10-20 18:30:02',
        isVisible: true
    },
    {
        id: 2,
        image: 'img1.jpeg',
        name: 'Pattaya Park Tower',
        updatedAt: '2024-10-19 12:30:02',
        isVisible: false
    },
    {
        id: 3,
        image: 'img2.webp',
        name: 'Family Nest Project',
        updatedAt: '2024-10-19 12:30:02',
        isVisible: false
    },
    {
        id: 4,
        image: 'img3.webp',
        name: 'Courtyard',
        updatedAt: '2024-10-13 09:30:02',
        isVisible: false
    },
]);

const changeVisibility = (id) => {
    const index = points.findIndex(point => point.id === id);
    points[index].isVisible = !points[index].isVisible;
}
</script>

<template>
    <AppLayout title="Points">
        <template #header>
            <div class="flex flex-row justify-between align-middle">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Points list
                </h2>
                <div>
                    <PrimaryButton class="mr-3">Add point</PrimaryButton>
                    <SecondaryButton>Save all</SecondaryButton>
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
                            <th class="text-center px-2 w-28">Image</th>
                            <th class="text-left px-4">Building Name</th>
                            <th class="text-center px-4 w-52">Updated date</th>
                            <th class="text-center px-2 w-24">Is visible</th>
                            <th class="text-center px-2 w-32">Actions</th>
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
