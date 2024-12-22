<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import {onMounted, onUpdated, ref} from "vue";
import {router, usePage} from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import {Link} from "@inertiajs/vue3";
import InfoBanner from "@/Components/InfoBanner.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CategoryTableRow from "@/Pages/Admin/Partials/CategoryTableRow.vue";

const page = usePage();
const categories = ref([]);
const isDisabled = ref(false);
const isBannerShown = ref(false);
const bannerMessage = ref('test');
const bannerType = ref('success');
const isModalShown = ref(false);
const modalText = ref('');
const deletingCategory = ref({});

onMounted(() => {
    showBanner(page.props.flash.message);
    categories.value = page.props.categories;
});
onUpdated(() => {
    showBanner(page.props.flash.message);
    categories.value = page.props.categories;
});

const showBanner = (text = '', type = 'success') => {
    isBannerShown.value = true;
    bannerMessage.value = text;
    bannerType.value = type;
}

const closeBanner = () => {
    bannerMessage.value = '';
    isBannerShown.value = false;
}

const showDeleteModal = (category) => {
    modalText.value = 'Вы уверены что хотите удалить данную категорию?';
    isModalShown.value = true;
    deletingCategory.value = category;
}

const cancelDelete = () => {
    modalText.value = '';
    isModalShown.value = false;

    setTimeout(() => {
        deletingCategory.value = {};
    }, 300);
}

const save = async () => {
    isDisabled.value = true;

    router.post(route('categories.save'), categories.value, {
        onSuccess: (page) => {
            showBanner(page.props.flash.message);
        },
        onError: (errors) => {
            showBanner(errors.message, 'fail');
        },
        onFinish: visit => {
            isDisabled.value = false;
        }
    });
}

const remove = async (category) => {
    isDisabled.value = true;

    router.delete(route('categories.destroy', {id: category.id}), {
        onSuccess: (page) => {
            showBanner(page.props.flash.message);
        },
        onError: (errors) => {
            showBanner(errors.message, 'fail');
        },
        onFinish: visit => {
            isDisabled.value = false;
            isModalShown.value = false;
        }
    });
}
</script>

<template>
    <AppLayout title="Категории">
        <template #header>
            <div class="flex flex-row justify-between align-middle">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
                    Список категорий
                </h2>
                <div>
                    <Link
                        class="cursor-pointer inline-flex items-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150 mr-3"
                        :class="{'pointer-events-none opacity-50': isDisabled}"
                        :href="route('categories.create')"
                    >
                        Создать
                    </Link>
                </div>
            </div>
        </template>

        <InfoBanner
            :show="isBannerShown"
            :style="bannerType"
            :message="bannerMessage"
            @close="closeBanner"
        />

        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-xl sm:rounded-lg">
                    <table class="table-fixed w-full" v-if="categories.length > 0">
                        <thead class="h-12 border-b-2">
                        <tr>
                            <th class="text-center px-2 w-10">Id</th>
                            <th class="text-center px-2 w-28">Иконка</th>
                            <th class="text-left px-4">Название категории</th>
                            <th class="text-center px-4 w-52">Дата изменения</th>
                            <th class="text-center px-2 w-32">Действия</th>
                        </tr>
                        </thead>
                        <tbody>
                        <CategoryTableRow
                            v-for="category in categories"
                            :category="category"
                            :key="category.id"
                            @remove="showDeleteModal(category)"
                        />
                        </tbody>
                    </table>
                    <div class="p-6 text-center text-gray-400 font-medium" v-else>Нет ни одного добавленного категории.</div>
                </div>
            </div>
        </div>


        <Modal :show="isModalShown" max-width="md">
            <div class="py-8 px-12">
                <h2 class="">Вы точно хотите удалить данный категорию?</h2>
                <div class="info mt-6 text-sm">
                    <div>
                        <span>Название категорию:</span>
                        <p>{{deletingCategory.name}}</p>
                    </div>
                    <div class="mt-3">
                        <span>Иконка категорию:</span>
                        <img :src="deletingCategory.icon" :alt="deletingCategory.name">
                    </div>
                </div>
                <div class="actions mt-6 w-full flex justify-end">
                    <SecondaryButton
                        @click="remove(deletingCategory)"
                        class="mr-2 hover:bg-red-400 hover:text-white hover:border-red-400"
                    >
                        Удалить
                    </SecondaryButton>
                    <PrimaryButton @click="cancelDelete">Отмена</PrimaryButton>
                </div>
            </div>
        </Modal>
    </AppLayout>
</template>
