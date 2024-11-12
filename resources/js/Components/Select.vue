<script setup>
import Dropdown from "@/Components/Dropdown.vue";
import {onUpdated, ref, watch} from 'vue';
import SelectOption from "@/Components/SelectOption.vue";

const props = defineProps({
    options: {
        type: Array,
        required: true,
    },
    selected: {
        type: Number,
        required: false,
        default: 0,
    }
});

defineEmits(['update:selected']);

const selectedOptionName = ref('');

onUpdated(() => {
    selectedOptionName.value = props.options.filter(item => item.id === props.selected)[0]?.name;
})
</script>

<template>
    <Dropdown align="left" class="mt-1">
        <template #trigger>
            <span class="inline-flex rounded-md">
                <button type="button" class="inline-flex items-center px-3 py-2 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-500 dark:text-gray-400 bg-white dark:bg-gray-800 hover:text-gray-700 dark:hover:text-gray-300 focus:outline-none focus:bg-gray-50 dark:focus:bg-gray-700 active:bg-gray-50 dark:active:bg-gray-700 transition ease-in-out duration-150">
                    <span v-if="!selectedOptionName">Выбрать категорию</span>
                    <span v-else>{{selectedOptionName}}</span>
                    <svg class="ms-2 -me-0.5 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 15L12 18.75 15.75 15m-7.5-6L12 5.25 15.75 9" />
                    </svg>
                </button>
            </span>
        </template>

        <template #content>
            <div class="items">
                <SelectOption
                    v-for="option in options"
                    :option="option"
                    :key="option.id"
                    :is-selected="option.id === props.selected"
                    @click="$emit('update:selected', option.id)"
                />
            </div>
        </template>
    </Dropdown>
</template>

<style scoped>

</style>
