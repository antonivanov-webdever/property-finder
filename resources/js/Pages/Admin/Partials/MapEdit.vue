<script setup>
import {computed, ref, watch} from "vue";
import {usePage} from "@inertiajs/vue3";

const emit = defineEmits(['update:address', 'update:coordinates']);
const page = usePage();
const coordinates = ref(null);
const long = ref('');
const lat = ref('');
const address = ref('');
const city = ref('');
const district = ref('');
const street = ref('');
const house = ref('');
const fullAddress = computed({
    get() {
        let result = '';

        if (city.value) {
            result = `${city.value}`;
        }

        if (district.value) {
            result += `, ${district.value}`;
        }

        if (street.value) {
            result += `, ${street.value}`;
        }

        if (house.value) {
            result += `, ${house.value}`;
        }

        emit('update:address', result);
        return result;
    },
    set() {
        city.value = '';
        district.value = '';
        street.value = '';
        house.value = '';
    }
});

watch(coordinates, (newValue) => {
    lat.value = newValue[0];
    long.value = newValue[1]
});

ymaps.ready(init);

function init() {
    let placemark = undefined;
    const myMap = new ymaps.Map("map", {
        center: [55.76, 37.64],
        zoom: 8
    });

    if (page.props.point?.address) {
        coordinates.value = JSON.parse(page.props.point.coordinates);

        setAddressByCoordinates();
        addPlaceMark();
    }

    myMap.events.add('click', function (e) {
        fullAddress.value = '';
        coordinates.value = e.get('coords');
        emit('update:coordinates', coordinates);

        if (placemark) {
            myMap.geoObjects.remove(placemark);
        }

        setAddressByCoordinates();
        addPlaceMark();
    });

    function addPlaceMark() {
        placemark = new ymaps.Placemark(coordinates.value, {}, {
            preset: "islands#circleDotIcon",
            iconColor: '#1f2937'
        });

        myMap.geoObjects.add(placemark);
    }

    function setAddressByCoordinates() {
        ymaps.geocode(coordinates.value, {
            results: 1
        }).then((res) => {
            address.value = res.geoObjects.get(0).properties.get('name');
            const properties = res.geoObjects.get(0).properties.getAll();
            const addressProp = properties.metaDataProperty.GeocoderMetaData.Address.Components;

            addressProp.forEach(item => {
                switch (item.kind) {
                    case 'area':
                        city.value = item.name;
                        break;
                    case 'street':
                        street.value = item.name;
                        break;
                    case 'house':
                        house.value = item.name;
                        break;
                }
            })
        });

        ymaps.geocode(coordinates.value, {
            kind: 'district',
            results: 1
        }).then((res) => {
            address.value = res.geoObjects.get(0).properties.get('name');
            const properties = res.geoObjects.get(0).properties.getAll();
            const addressProp = properties.metaDataProperty.GeocoderMetaData.Address.Components;
            let districtName = '';

            addressProp.forEach(item => {
                switch (item.kind) {
                    case 'locality':
                        city.value = item.name;
                        break;
                    case 'district':
                        districtName = districtName || item.name;
                        district.value = districtName;
                        break;
                }
            })
        });
    }
}
</script>

<template>
    <div class="map-container">
        <div id="map" class="w-auto h-96 mt-2 mb-4"></div>
        <div class="info">
            <div class="pb-2 font-medium text-sm text-gray-700 dark:text-gray-300">
                <span>Координаты:</span>
                {{ coordinates }}
            </div>
            <div class="py-2 font-medium text-sm text-gray-700 dark:text-gray-300 hidden">
                <span>Адрес:</span>
                {{ fullAddress }}
            </div>
        </div>
    </div>
</template>
