import { onMounted } from "vue";

export function useYandexMaps(callback) {
    onMounted(() => {
        if (typeof window !== "undefined") {
            if (!window.ymaps) {
                const script = document.createElement("script");
                script.src = "https://api-maps.yandex.ru/2.1/?apikey=0fa19295-08a6-4459-b203-b32cd272ccad&lang=ru_RU";
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
