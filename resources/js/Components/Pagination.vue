<script setup>
import { Link } from '@inertiajs/vue3';

const props = defineProps({
    links: Array
})
</script>

<template>
    <div>
        <div class="flex flex-wrap -mb-1">
            <template v-for="(link, key) in links">
                <div v-if="link.url === null" :key="key" class="mb-1 mr-1 px-4 py-3 text-gray-400 dark:text-gray-200 text-sm leading-4 border rounded" v-html="link.label" />
                <Link v-else :key="`link-${key}`" class="mb-1 mr-1 px-4 py-3 text-sm leading-4
                focus:text-indigo-500 focus:border-indigo-500 dark:focus:border-gray-500
                hover:bg-white dark:hover:bg-gray-200 dark:hover:text-gray-800 dark:focus:text-gray-800
                border rounded" :class="{ 'bg-white dark:bg-gray-200 dark:text-gray-800': link.active }" :href="link.url" v-html="link.label" />
            </template>
        </div>
    </div>
</template>
