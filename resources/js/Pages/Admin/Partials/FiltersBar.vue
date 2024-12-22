<script setup>
import Select from "@/Components/Select.vue";
import {onMounted, onUpdated, ref} from "vue";
import SecondaryButton from "@/Components/SecondaryButton.vue";

const props = defineProps(['filterForm']);
const categoryFilterOptions = ref([]);

const imageFilterOptions = ref([
    {id: 0, name: 'С фото', value: false},
    {id: 1, name: 'Без фото', value: true},
]);
const visibilityFilterOptions = ref([
    {id: 0, name: 'Невидимые', value: false},
    {id: 1, name: 'Видимые', value: true},
]);

onMounted(async () => {
    imageFilterOptions.value = [
        {id: 0, name: 'Без фото', value: false},
        {id: 1, name: 'С фото', value: true},
    ];
    visibilityFilterOptions.value = [
        {id: 0, name: 'Невидимые', value: false},
        {id: 1, name: 'Видимые', value: true},
    ];

    try {
        const response = await axios(route('categories.getAll'));
        categoryFilterOptions.value = response.data;
    } catch (e) {
        console.log(e);
    }
});

onUpdated(() => {

})

</script>

<template>
  <div class="filters-bar flex flex-wrap gap-6">
    <Select :options="imageFilterOptions"
            :selected="filterForm.hasImage ?? undefined"
            v-model:selected="filterForm.hasImage"
            label="Фильтр по наличию фото"
            @update:selected="$emit('change')"
    />
    <Select :options="categoryFilterOptions"
            :selected="filterForm.category_id ?? undefined"
            v-model:selected="filterForm.category_id"
            label="Фильтр по категории"
            @update:selected="$emit('change')"
    />
    <Select :options="visibilityFilterOptions"
            :selected="filterForm.isVisible ?? undefined"
            v-model:selected="filterForm.isVisible"
            label="Фильтр по видимости"
            @update:selected="$emit('change')"
    />
      <SecondaryButton @click="$emit('reset')">
          <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4 mr-1">
              <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12" />
          </svg>
          Сбросить фильтры
      </SecondaryButton>
  </div>
</template>
