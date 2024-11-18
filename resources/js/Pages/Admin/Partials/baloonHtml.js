export const baloonHtml = `
<div class="ya-popover">
    <a class="close" href="#">&times;</a>
    <div class="arrow"></div>
    <div class="ya-popover-inner">
        $[[options.contentLayout observeSize minWidth=300 maxWidth=300 maxHeight=350]]
    </div>
</div>
`;

export const baloonContentHtml = `
<div class="ya-popover-header">
    $[properties.tags]
    <div class="ya-popover-caption">{{ properties.cap }}</div>
    $[properties.image]
</div>
<div class="ya-popover-content">
    $[properties.address]

    {% if properties.obj_type %}
    <div>
        <span>Тип: </span>
        {{ properties.obj_type }}
    </div>
    {% endif %}

    {% if properties.obj_status %}
    <div>
        <span>Этап: </span>
        {{ properties.obj_status }}
        <span class="ya-popover-content-os ya-popover-content-os-{{ properties.obj_status_int }}"></span>
    </div>
    {% endif %}

    {% if properties.developer %}
    <div>
        <span>Застройщик:</span>
        {{ properties.developer|raw }}
    </div>
    {% endif %}

    {% if properties.contractor %}
        <div>
            <span>Генподрядчик:</span>
            {{ properties.contractor|raw }}
        </div>
    {% endif %}

    {% if properties.constructr %}
        <div>
            <span>Проектировщик:</span>
            {{ properties.constructr|raw }}
        </div>
    {% endif %}
    <div class="ya-popover-content-link">$[properties.url]</div>
</div>
`;
