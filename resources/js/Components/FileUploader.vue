<script setup>
import {onMounted, ref} from 'vue';

const props = defineProps({
    image: File | String,
});

defineEmits(['update:image']);

const file = ref(null);
const fileName = ref('Фото не выбрано');
const buttonText = ref('Загрузить');

onMounted(() => {
    if (file.value.hasAttribute('autofocus')) {
        file.value.focus();
    }

    if (typeof props.image === 'string') {
        fileName.value = '';
        buttonText.value = 'Заменить';
    }
});

const updateModelValue = () => {
    fileName.value = file.value.files[0]?.name ?? 'Фото не выбрано';
}

</script>

<template>
    <div class="flex items-center text-gray-400">
        <button
            type="button"
            @click.prevent="file.click()"
            class="p-1 text-xs text-slate-55 uppercase tracking-wider
                py-2 px-4 rounded-md border border-gray-300
                bg-white text-gray-700 font-semibold
                hover:bg-gray-100
                cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
        >
            {{ buttonText }}
        </button>
        <span class="text-sm text-slate-500 cursor-text ml-4">{{ fileName }}</span>
        <input
            ref="file"
            type="file"
            @input="$emit('update:image', $event.target.files[0])"
            @change="updateModelValue"
            class="hidden"
        />
    </div>
</template>


