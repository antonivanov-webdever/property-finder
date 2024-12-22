<script setup>
import InputLabel from "@/Components/InputLabel.vue";
import TextInput from "@/Components/TextInput.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";

const props = defineProps(['searchForm']);
</script>

<template>
  <form class="search-form flex flex-nowrap w-full items-end" @submit.prevent="$emit('submit')">
    <div class="form-group w-full mr-4">
      <InputLabel for="name" value="Поиск по названию объекта или id" />
      <TextInput
          id="search"
          v-model="searchForm.q"
          type="text"
          class="block w-full mt-1"
      />
    </div>
    <PrimaryButton class="shrink-0 py-3 px-6">Поиск</PrimaryButton>
  </form>
</template>
