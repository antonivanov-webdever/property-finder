<script setup>
import {onMounted, ref} from "vue";
import FilterTab from "@/Pages/Admin/Partials/FilterTab.vue";

const filters = ref([]);

const test = (e) => {
    console.log(e)
}

onMounted(async () => {
    try {
        const response = await axios(route('filters.getAll'));
        filters.value = response.data;
    } catch (e) {
        console.log(e);
    }
});
</script>

<template>
    <div class="py-2 flex">
        <FilterTab
            v-for="filter in filters"
            :key="filter.id"
            :filter="filter"
            @update:checked="test"
        />
    </div>
</template>

<style scoped>

</style>
