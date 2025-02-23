<script setup>
import {onMounted, onUpdated, ref} from 'vue';

const props = defineProps({
    image: File | String,
    size: String,
});

const emit = defineEmits(['update:image']);

const file = ref(null);
const fileName = ref('Фото не выбрано');
const buttonText = ref('Загрузить');
const imageSrc = ref('');
const size = ref('');

onMounted(() => {
    if (props.size) {
        size.value = props.size === 'xs' ? 'max-w-10' : 'max-md:max-w-72';
    }

    if (file.value.hasAttribute('autofocus')) {
        file.value.focus();
    }

    if (props.image) {
        imageSrc.value = props.image;
    }
})

onUpdated(() => {
    size.value = props.size === 'xs' ? 'max-w-10' : 'max-md:max-w-80';

    if (imageSrc.value) {
        const filename = props.image;

        if (filename && !filename.includes('placeholder')) {
            buttonText.value = 'Заменить';
            fileName.value = '';
        }
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
        <img class="mt-2 mb-3 rounded-md" :class="[size]" v-if="imageSrc" :src="imageSrc">
        <div class="control-container">
            <button
                type="button"
                @click.prevent="file.click()"
                class="p-1 text-xs text-slate-55 uppercase tracking-wider
                py-2 px-4 rounded-md border border-gray-300
                bg-white text-gray-700 font-semibold
                hover:bg-gray-100
                cursor-pointer focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500
                max-sm:w-full"
            >
                {{ buttonText }}
            </button>
            <span class="text-sm text-slate-500 cursor-text sm:ml-4">{{ fileName }}</span>
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


