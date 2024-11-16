<script setup>
import {usePage} from "@inertiajs/vue3";

const page = usePage();

ymaps.ready(init);

function init() {
    const myMap = new ymaps.Map("map", {
        center: [55.76, 37.64],
        zoom: 9,
        controls: ["fullscreenControl", "rulerControl", "typeSelector", "zoomControl", "trafficControl", "searchControl"]
    });

    const objectManager = new ymaps.ObjectManager({
        clusterize: false,
        gridSize: 32,
        clusterDisableClickZoom: false
    });

    objectManager.objects.options.set("iconLayout", "default#image");
    objectManager.objects.options.set("iconImageSize", [16, 16]);
    objectManager.objects.options.set("iconImageOffset", [-8, -8]);
    objectManager.objects.options.set("hideIconOnBalloonOpen", false);
    objectManager.clusters.options.set("preset", "islands#greenClusterIcons");

    objectManager.objects.events.add('click', function(event) {
        const objectId = event.get('objectId');
        const objectState = objectManager.getObjectState(objectId);
        if (objectState.isClustered) {
            objectManager.clusters.balloon.open(objectState.cluster.id);
        } else {
            objectManager.objects.balloon.open(objectId);
        }
    });

    objectManager.objects.events.add('balloonopen', function(event) {
        const objectId = event.get('objectId');
        const object = objectManager.objects.getById(objectId);

        if (event.properties && objectManager.objects.baloon.isOpen(objectId)) {
            objectManager.objects.baloon.setData(object);
        }
    })

    myMap.events.add('click', function() {
        if (myMap.balloon.isOpen()) {
            myMap.balloon.close()
        }
    });

    myMap.geoObjects.add(objectManager);

    axios.get(route('points.getPointsOMJson'), {
        headers: {
            "Content-Type": "application/json"
        }
    }).then(res => {
        const object = ymaps.templateLayoutFactory.createClass('<div class="ya-popover"><a class="close" href="#">&times;</a><div class="arrow"></div><div class="ya-popover-inner">$[[options.contentLayout observeSize minWidth=300 maxWidth=300 maxHeight=350]]</div></div>', {
            build: function() {
                this.constructor.superclass.build.call(this);
                this._$element = this.getParentElement().querySelector(".ya-popover");
                this.applyElementOffset();
                this._$element.querySelector(".close").addEventListener("click", this.onCloseClick.bind(this));
            },
            clear: function() {
                this._$element.querySelector(".close").removeEventListener("click", this.onCloseClick);
                this.constructor.superclass.clear.call(this);
            },
            onSublayoutSizeChange: function() {
                object.superclass.onSublayoutSizeChange.apply(this, arguments);
                if (this._isElement(this._$element)) {
                    this.applyElementOffset();
                    this.events.fire("shapechange");
                }
            },
            applyElementOffset: function() {
                this._$element.style.left = -this._$element.offsetWidth / 2 + 'px';
                this._$element.style.top = -(this._$element.offsetHeight + this._$element.querySelector(".arrow").offsetHeight) + 'px';
            },
            onCloseClick: function(e) {
                e.preventDefault();
                this.events.fire("userclose");
            },
            getShape: function() {
                if (!this._isElement(this._$element)) {
                    return object.superclass.getShape.call(this);
                }

                const position = {
                    top: this._$element.offsetTop,
                    left: this._$element.offsetLeft
                };

                return new ymaps.shape.Rectangle(new ymaps.geometry.pixel.Rectangle(
                    [
                        [position.left, position.top],
                        [position.left + this._$element.offsetWidth, position.top + this._$element.offsetHeight + this._$element.querySelector(".arrow").offsetHeight]
                    ]
                ))
            },
            _isElement: function(element) {
                return element && element.querySelector(".arrow");
            }
        });

        const content = ymaps.templateLayoutFactory.createClass('<div class="ya-popover-header">$[properties.tags]<div class="ya-popover-caption">{{ properties.cap }}</div>$[properties.image]</div><div class="ya-popover-content">$[properties.address]{% if properties.obj_type %}<div><span>Тип: </span>{{ properties.obj_type }}</div>{% endif %}{% if properties.obj_status %}<div><span>Этап: </span>{{ properties.obj_status }}<span class="ya-popover-content-os ya-popover-content-os-{{ properties.obj_status_int }}"></span></div>{% endif %}{% if properties.developer %}<div><span>Застройщик:</span> {{ properties.developer|raw }}</div>{% endif %}{% if properties.contractor %}<div><span>Генподрядчик:</span> {{ properties.contractor|raw }}</div>{% endif %}{% if properties.constructr %}<div><span>Проектировщик:</span> {{ properties.constructr|raw }}</div>{% endif %}<div class="ya-popover-content-link">$[properties.url]</div></div>');
        objectManager.objects.options.set("balloonLayout", object);
        objectManager.objects.options.set("balloonContentLayout", content);
        objectManager.add(res.data)
    })
}
</script>

<template>
    <div class="map-container p-6">
        <div id="map" class="mt-2 mb-4 border"></div>
    </div>
</template>
