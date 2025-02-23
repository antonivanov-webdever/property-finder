<script setup>
import AppLayout from '@/Layouts/AppLayout.vue';
import { onMounted, onUpdated, ref } from "vue";
import { router, useForm, usePage } from "@inertiajs/vue3";
import { Link } from "@inertiajs/vue3";
import SecondaryButton from "@/Components/SecondaryButton.vue";
import PointsTableRow from "@/Pages/Admin/Partials/PointsTableRow.vue";
import InfoBanner from "@/Components/InfoBanner.vue";
import Modal from "@/Components/Modal.vue";
import PrimaryButton from "@/Components/PrimaryButton.vue";
import CsvUploader from "@/Components/CsvUploader.vue";
import Pagination from "@/Components/Pagination.vue";
import SearchForm from "@/Pages/Admin/Partials/SearchForm.vue";
import FiltersBar from "@/Pages/Admin/Partials/FiltersBar.vue";

const page = usePage();
const points = ref([]);
const links = ref([]);
const isDisabled = ref(false);
const isBannerShown = ref(false);
const bannerMessage = ref('test');
const bannerType = ref('success');
const isModalShown = ref(false);
const modalText = ref('');
const deletingPoint = ref({});

const csvForm = useForm({
    csv: null
});

const searchForm = useForm({
    q: page.props.q,
    hasImage: page.props.filters?.hasImage ?? undefined,
    isVisible: page.props.filters?.isVisible ?? undefined,
    category_id: page.props.filters?.category_id ?? undefined,
});

onMounted(() => {
    showBanner(page.props.flash.message);
    points.value = page.props.points.data;
    links.value = page.props.points.links;
});
onUpdated(() => {
    showBanner(page.props.flash.message);
    points.value = page.props.points.data;
    links.value = page.props.points.links;
});

const resetFilters = () => {
    if (searchForm.isVisible === undefined && searchForm.category_id === undefined && searchForm.hasImage === undefined) {
        return false;
    }

    if (searchForm.q) {
        searchForm.isVisible = undefined;
        searchForm.hasImage = undefined;
        searchForm.category_id = undefined;

        searchForm.get(route('points.search'));
    } else {
        router.get(route('points.index'));
    }
}

const uploadCsv = () => {
    isDisabled.value = true;

    csvForm.post(route('csv'), {
        onFinish: params => {
            isDisabled.value = false;
        }
    })
}

const showBanner = (text = '', type = 'success') => {
    isBannerShown.value = true;
    bannerMessage.value = text;
    bannerType.value = type;
}

const closeBanner = () => {
    bannerMessage.value = '';
    isBannerShown.value = false;
}

const showDeleteModal = (point) => {
    modalText.value = 'Вы уверены что хотите удалить данную точку?';
    isModalShown.value = true;
    deletingPoint.value = point;
}

const cancelDelete = () => {
    modalText.value = '';
    isModalShown.value = false;

    setTimeout(() => {
        deletingPoint.value = {};
    }, 300);
}

const changeVisibility = (id) => {
    const index = points.value.findIndex(point => point.id === id);
    points.value[index].is_visible = !points.value[index].is_visible;
}

const save = async () => {
    isDisabled.value = true;

    router.post(route('points.save'), points.value, {
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

const remove = async (point) => {
    isDisabled.value = true;

    router.delete(route('points.destroy', {id: point.id}), {
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
    <AppLayout title="Points">
        <template #header>
            <div class="flex flex-row justify-between align-middle max-sm:flex-col">
                <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight max-sm:mb-4 max-sm:text-center">
                    Список точек
                </h2>
                <div class="max-sm:flex max-sm:flex-col mx-auto">
                    <CsvUploader
                        class="sm:mr-3 max-sm:mb-4"
                        v-model="csvForm.csv"
                        @update:modelValue="uploadCsv"
                        :is-disabled="isDisabled"
                    />
                    <Link
                        class="cursor-pointer inline-flex items-center max-sm:justify-center px-4 py-2 bg-gray-800 dark:bg-gray-200 border border-transparent rounded-md font-semibold text-xs text-white dark:text-gray-800 uppercase tracking-widest hover:bg-gray-700 dark:hover:bg-white focus:bg-gray-700 dark:focus:bg-white active:bg-gray-900 dark:active:bg-gray-300 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 dark:focus:ring-offset-gray-800 disabled:opacity-50 transition ease-in-out duration-150 sm:mr-3 max-sm:mb-4"
                        :class="{'pointer-events-none opacity-50': isDisabled}"
                        :href="route('points.create')"
                    >
                        Создать
                    </Link>
                    <SecondaryButton
                        class="max-sm:justify-center"
                        :class="{'disabled:opacity-50': isDisabled}"
                        @click="save"
                        :disabled="isDisabled"
                    >
                        Сохранить
                    </SecondaryButton>
                </div>
            </div>
        </template>

        <InfoBanner
            :show="isBannerShown"
            :style="bannerType"
            :message="bannerMessage"
            @close="closeBanner"
        />

        <div class="pt-4 sm:pt-12 pb-4 px-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 shadow-md sm:rounded-lg">
                    <div class="search p-6">
                        <SearchForm class="mb-4 max-sm:mb-6" :search-form="searchForm" @submit="searchForm.get(route('points.search'))" />

                        <FiltersBar
                            :filter-form="searchForm"
                            @change="searchForm.get(route('points.search'))"
                            @reset="resetFilters"
                        />
                    </div>
                </div>
            </div>
        </div>
        <div class="pt-4 pb-12 max-sm:px-4">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-md sm:rounded-lg">
                    <div class="w-full overflow-x-auto" v-if="points">
                        <table class="table-fixed table-container">
                            <thead class="h-12 border-b-2">
                            <tr>
                                <th class="text-center px-2 w-10">Id</th>
                                <th class="text-center px-2 w-28">Фото</th>
                                <th class="text-left px-4">Название объекта</th>
                                <th class="text-center px-4 w-52">Категория</th>
                                <th class="text-center px-4 w-52 max-xl:hidden">Дата изменения</th>
                                <th class="text-center px-2 w-24">Видимость</th>
                                <th class="text-center px-2 w-32">Действия</th>
                            </tr>
                            </thead>
                            <tbody>
                            <PointsTableRow
                                v-for="point in points"
                                :point="point"
                                :key="point.id"
                                @change-visibility="changeVisibility"
                                @remove="showDeleteModal(point)"
                            />
                            </tbody>
                        </table>
                    </div>
                    <div class="p-6 text-center text-gray-400 font-medium" v-else>Нет ни одной добавленной точки.</div>
                </div>
                <div class="pagination shadow-lg p-6 w-full flex justify-center bg-white rounded-lg mt-8">
                    <Pagination :links="links" />
                </div>
            </div>
        </div>


        <Modal :show="isModalShown" max-width="md">
            <div class="py-8 px-12">
                <h2 class="">Вы точно хотите удалить данную точку?</h2>
                <div class="info mt-6 text-sm">
                    <div>
                        <span>Название объекта:</span>
                        <p>{{deletingPoint.name}}</p>
                    </div>
                    <div class="mt-4">
                        <span>Адрес объекта:</span>
                        <p>{{deletingPoint.address}}</p>
                    </div>
                    <div class="mt-3">
                        <span>Фото объекта:</span>
                        <img :src="deletingPoint.image" :alt="deletingPoint.name">
                    </div>
                </div>
                <div class="actions mt-6 w-full flex justify-end">
                    <SecondaryButton
                        @click="remove(deletingPoint)"
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

<style scoped>
.table-container {
    width: 100%;
    min-width: 900px;
}
</style>
