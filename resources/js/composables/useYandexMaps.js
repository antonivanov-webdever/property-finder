import { onMounted } from "vue";
import { usePage } from "@inertiajs/vue3";

export function useYandexMaps(callback) {
    const page = usePage();

    onMounted(() => {
        if (typeof window !== "undefined") {
            if (!window.ymaps) {
                const apiKey = page.props.maps?.api_key ?? '';
                const script = document.createElement("script");
                script.src = `https://api-maps.yandex.ru/2.1/?apikey=${apiKey}&lang=ru_RU`;
                script.onload = () => {
                    window.ymaps.ready(callback);
                };
                document.head.appendChild(script);
            } else {
                window.ymaps.ready(callback);
            }
        }
    });
}
