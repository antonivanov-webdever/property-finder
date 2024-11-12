<script setup>

import {Link, useForm} from "@inertiajs/vue3";
import AppLayout from "@/Layouts/AppLayout.vue";
import InputError from "@/Components/InputError.vue";
import TextInput from "@/Components/TextInput.vue";
import InputLabel from "@/Components/InputLabel.vue";
import Textarea from "@/Components/Textarea.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import FileUploader from "@/Components/FileUploader.vue";
import Select from "@/Components/Select.vue";
import {onMounted, ref, watch} from "vue";

const form = useForm({
    image: '',
    name: '',
    address: '',
    description: '',
    tgLink: '',
    youtubeLink: '',
    filter: 0,
});

const filters = ref([]);
const selected = ref(0);

onMounted(async () => {
   try {
       const response = await axios(route('filters.getAll'));
       filters.value = response.data;
   } catch (e) {
       console.log(e);
   }
});

const submit = () => {
    console.log(form)
}
</script>

<template>
    <AppLayout title="Create Point">
        <template #header>
            <div class="flex flex-row justify-between align-middle">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Создание новой точки
                </h2>
                <div>
                    <Link
                        class="inline-flex items-center px-4 py-2 bg-white dark:bg-gray-800 border border-gray-300 dark:border-gray-500 rounded-md font-semibold text-xs text-gray-700 dark:text-gray-300 uppercase tracking-widest shadow-sm hover:bg-gray-50 dark:hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-25 transition ease-in-out duration-150 mr-3"
                        :href="route('points.index')"
                    >
                        Back to List
                    </Link>
                    <PrimaryButton
                        class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150"
                        @click="submit"
                    >
                        Save
                    </PrimaryButton>
                </div>
            </div>
        </template>

        <div class="p-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <form>
                        <div class="form-wrapper md:grid md:grid-cols-2">
                            <div class="md:col-span-1 flex-col justify-between p-6 border-r border-r-gray-200">
                                <div class="">
                                    <InputLabel for="image" value="Фото объекта" />
                                    <FileUploader
                                        id="image"
                                        v-model="form.image"
                                        class="block w-full mt-1"
                                    />
                                    <InputError :message="form.errors.image" class="mt-2" />
                                </div>
                                <div class="mt-6">
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
                            <div class="mt-5 md:mt-0 md:col-span-1 p-6">
                                <div id="map"></div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>

    </AppLayout>
</template>

<style scoped>

</style>
