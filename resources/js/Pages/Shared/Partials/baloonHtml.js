export const baloonHtml = `
<div class="ya-popover">
    <a class="close" href="#">&times;</a>
    <div class="arrow"></div>
    <div class="ya-popover-inner">
        $[[options.contentLayout observeSize minWidth=600 maxWidth=600 maxHeight=550]]
    </div>
</div>
`;

export const baloonContentHtml = `
<div class="ya-popover-content">
    <div class="ya-popover-image">
        $[properties.image]
    </div>
    <div class="ya-popover-info">
        <div class="ya-popover-name">{{ properties.name }}</div>
        <div class="ya-popover-address">
            $[properties.address]
        </div>

        {% if properties.description %}
            <div class="ya-popover-description">
                $[properties.description]
            </div>
        {% endif %}

        <div class="ya-popover-links">
            {% if properties.tg_link %}
                $[properties.tg_link]
            {% endif %}

            {% if properties.youtube_link %}
                $[properties.youtube_link]
            {% endif %}
        </div>
    </div>
</div>
`;
