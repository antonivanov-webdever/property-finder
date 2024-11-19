<script setup>
import {onMounted, ref, unref} from "vue";
import FilterTab from "@/Pages/Admin/Partials/FilterTab.vue";

const emit = defineEmits(['update:filters']);
const filters = ref([]);

const updateFilters = (filter) => {
    const updatedFilter = unref(filters).filter(item => item.id === filter.id);
    updatedFilter.forEach(item => item.isChecked = filter.isChecked);
    const checkedFilters = filters.value.filter(item => item.isChecked);
    const checkedFiltersIds = checkedFilters.map(item => item.id);
    emit('update:filters', checkedFiltersIds);
}

onMounted(async () => {
    try {
        const response = await axios(route('filters.getAll'));
        filters.value = response.data;
        filters.value.forEach(filter => filter.isChecked = true);
    } catch (e) {
        console.log(e);
    }
});
</script>

<template>
    <div class="pt-2 flex flex-wrap">
        <FilterTab
            v-for="filter in filters"
            :key="filter.id"
            :filter="filter"
            @update:checked="updateFilters"
        />
    </div>
</template>

<style scoped>

</style>
