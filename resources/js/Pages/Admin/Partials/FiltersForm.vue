<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import FileUploader from "@/Components/FileUploader.vue";
import InputError from "@/Components/InputError.vue";

const props = defineProps(['form']);
</script>

<template>
    <form>
        <div class="form-wrapper md:grid md:grid-cols-1">
            <div class="md:col-span-1 flex-col justify-between p-6 border-r border-r-gray-200">
                <div class="">
                    <InputLabel for="name" value="Название фильтра" />
                    <TextInput
                        id="name"
                        v-model="form.name"
                        type="text"
                        class="block w-full mt-1"
                    />
                    <InputError :message="form.errors.name" class="mt-2" />
                </div>
                <div class="mt-6">
                    <InputLabel for="image" value="Иконка фильтра" />
                    <FileUploader
                        id="image"
                        v-model:image="form.icon"
                        class="block w-80 mt-1"
                    />
                    <InputError :message="form.errors.icon" class="mt-2" />
                </div>
            </div>
        </div>
    </form>
</template>

<style scoped>

</style>
