<script setup>
import {onMounted, ref, unref} from "vue";
import CategoryFilterTab from "@/Pages/Shared/Partials/CategoryFilterTab.vue";

const emit = defineEmits(['update:categoryFilter']);
const categories = ref([]);

const updateCategoryFilter = (filter) => {
    const updatedFilter = unref(categories).filter(item => item.id === filter.id);
    updatedFilter.forEach(item => item.isChecked = filter.isChecked);
    const checkedCategories = categories.value.filter(item => item.isChecked);
    const checkedCategoriesIds = checkedCategories.map(item => item.id);
    emit('update:categoryFilter', checkedCategoriesIds);
}

onMounted(async () => {
    try {
        const response = await axios(route('categories.getAll'));
        categories.value = response.data;
        categories.value.forEach(filter => filter.isChecked = true);
    } catch (e) {
        console.log(e);
    }
});
</script>

<template>
    <div class="pt-2 flex flex-wrap">
        <CategoryFilterTab
            v-for="category in categories"
            :key="category.id"
            :category="category"
            @update:checked="updateCategoryFilter"
        />
    </div>
</template>

<style scoped>

</style>
