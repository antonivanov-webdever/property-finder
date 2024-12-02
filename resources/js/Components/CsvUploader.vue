<script setup>
import { ref } from 'vue';

defineProps({
    modelValue: String | File,
    isDisabled: Boolean,
});

const csv = ref(null);

defineEmits(['update:modelValue']);
</script>

<template>
    <div class="inline-block">
        <button
            type="button"
            @click.prevent="csv.click()"
            :disabled="isDisabled"
            class="p-1 text-xs text-slate-55 uppercase tracking-wider
                py-2 px-4 rounded-md border border-gray-300
                bg-white text-gray-700 font-semibold
                hover:bg-gray-50 dark:hover:bg-gray-700 disabled:opacity-50 disabled:pointer-events-none
                cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            Загрузить CSV
        </button>
        <input
            type="file"
            name="csv"
            ref="csv"
            accept="text/csv"
            class="hidden"
            @input="$emit('update:modelValue', $event.target.files[0])"
        >
    </div>
</template>
