<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import Textarea from "@/Components/Textarea.vue";
import TextInput from "@/Components/TextInput.vue";
import Select from "@/Components/Select.vue";
import FileUploader from "@/Components/FileUploader.vue";
import InputError from "@/Components/InputError.vue";
import Map from "@/Pages/Admin/Partials/Map.vue";
import {onMounted, ref} from "vue";

const props = defineProps(['form']);

const filters = ref([]);

onMounted(async () => {
    try {
        const response = await axios(route('filters.getAll'));
        filters.value = response.data;
    } catch (e) {
        console.log(e);
    }
});
</script>

<template>
    <form>
        <div class="form-wrapper md:grid md:grid-cols-8">
            <div class="md:col-span-3 flex-col justify-between p-6 border-r border-r-gray-200">
                <div class="">
                    <InputLabel for="name" value="Название объекта" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="block w-full mt-1"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>
                <div class="mt-6">
                    <InputLabel for="image" value="Фото объекта" />
                    <FileUploader
                        id="image"
                        v-model:image="form.image"
                        class="block w-full mt-1"
                    />
                    <InputError :message="form.errors.image" class="mt-2" />
                </div>
                <div class="mt-6">
                    <InputLabel for="filter" value="Категория объекта" />
                    <Select
                        :options="filters"
                        :selected="form.filter"
                        v-model:selected="form.filter"
                        class="w-48"
                    />
                    <InputError :message="form.errors.filter" class="mt-2" />
                </div>
                <div class="mt-6">
                    <InputLabel for="description" value="Описание объекта" />
                    <Textarea
                        id="description"
                        v-model="form.description"
                        type="text"
                        class="block w-full mt-1"
                    />
                    <InputError :message="form.errors.description" class="mt-2" />
                </div>
                <div class="mt-6">
                    <InputLabel for="tgLink" value="Ссылка на Telegram" />
                    <TextInput
                        id="tgLink"
                        v-model="form.tgLink"
                        type="text"
                        class="block w-full mt-1"
                    />
                    <InputError :message="form.errors.tgLink" class="mt-2" />
                </div>
                <div class="mt-6">
                    <InputLabel for="youtubeLink" value="Ссылка на Youtube" />
                    <TextInput
                        id="youtubeLink"
                        v-model="form.youtubeLink"
                        type="text"
                        class="block w-full mt-1"
                    />
                    <InputError :message="form.errors.youtubeLink" class="mt-2" />
                </div>
            </div>
            <div class="mt-5 md:mt-0 md:col-span-5 p-6">
                <div class="">
                    <InputLabel for="address" value="Адрес объекта" />
                    <TextInput
                        id="address"
                        v-model="form.address"
                        type="text"
                        class="block w-full mt-1"
                    />
                    <InputError :message="form.errors.address" class="mt-2" />
                </div>
                <div class="mt-6">
                    <InputLabel for="map" value="Выбрать точку на карте" />
                    <Map
                        v-model:address="form.address"
                        v-model:coordinates="form.coordinates"
                    />
                    <InputError :message="form.errors.coordinates" />
                </div>
            </div>
        </div>
    </form>
</template>

<style scoped>

</style>
