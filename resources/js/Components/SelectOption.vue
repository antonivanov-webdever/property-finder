<script setup>
defineProps({
    option: {
        type: Object,
        required: true,
    },
    isSelected: {
        type: Boolean,
        required: true,
        default: false,
    }
});
</script>

<template>
    <div class="block cursor-pointer px-4 py-2 text-start text-sm leading-5
                border-b border-gray-200 dark:border-gray-600 last:border-none text-gray-700 dark:text-gray-300
                hover:bg-gray-100 dark:hover:bg-gray-800 focus:outline-none focus:bg-gray-100
                dark:focus:bg-gray-800 transition duration-150 ease-in-out"
    >
        {{option.name}}
    </div>
</template>
