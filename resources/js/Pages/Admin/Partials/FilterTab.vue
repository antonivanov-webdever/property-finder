<script setup>


import {onMounted, ref} from "vue";
import Checkbox from "@/Components/Checkbox.vue";

const props = defineProps(['filter']);
const isChecked = ref(true);
</script>

<template>
    <label class="flex flex-row flex-nowrap mr-3">
        <Checkbox
            class="hidden"
            :checked="isChecked"
            v-model:checked="isChecked"
            @update:checked="$emit('update:checked', {isChecked, id:filter.id})"
        />
        <span
            class="flex flex-row flex-nowrap py-2 px-4 bg-sky-50 cursor-pointer rounded-3xl border-2"
            :class="{'border-purple-700 opacity-100': isChecked, 'border-gray-300 opacity-50': !isChecked }"
        >
            <img :src="filter.icon" :alt="filter.name" class="w-4 object-contain mr-1.5">
            {{ filter.name }}
        </span>
    </label>
</template>

<style scoped>

</style>
