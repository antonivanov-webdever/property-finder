<script setup>
import {onMounted, onUpdated, ref} from 'vue';

const props = defineProps({
    image: File | String,
});

const emit = defineEmits(['update:image']);

const file = ref(null);
const fileName = ref('Фото не выбрано');
const buttonText = ref('Загрузить');
const imageSrc = ref('');

onMounted(() => {
    if (file.value.hasAttribute('autofocus')) {
        file.value.focus();
    }

    if (props.image) {
        imageSrc.value = props.image;

    }
})

onUpdated(() => {
    if (imageSrc.value) {
        buttonText.value = 'Заменить';
    }
});

const updateModelValue = () => {
    fileName.value = file.value.files[0]?.name ?? 'Фото не выбрано';
}

const updateImage = (event) => {
    const reader = new FileReader();
    reader.onload = () => imageSrc.value = reader.result;
    reader.readAsDataURL(event.target.files[0]);
    emit('update:image', event.target.files[0]);
}
</script>

<template>
    <div class="flex flex-col text-gray-400">
        <img class="mt-2 mb-3 rounded-md" v-if="imageSrc" :src="imageSrc">
        <div class="control-container">
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
                @input="updateImage"
                @change="updateModelValue"
                class="hidden"
            />
        </div>
    </div>
</template>


