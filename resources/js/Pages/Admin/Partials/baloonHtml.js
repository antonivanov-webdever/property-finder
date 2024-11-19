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
<div class="ya-popover-header">
    <div class="ya-popover-image">
        $[properties.image]
    </div>
    <div class="ya-popover-info">
        <div class="ya-popover-name">{{ properties.name }}</div>
        <div class="ya-popover-address">
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-6">
              <path stroke-linecap="round" stroke-linejoin="round" d="M15 10.5a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
              <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 10.5c0 7.142-7.5 11.25-7.5 11.25S4.5 17.642 4.5 10.5a7.5 7.5 0 1 1 15 0Z" />
            </svg>

            $[properties.address]
        </div>
    </div>
</div>
<div class="ya-popover-content">
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
    <div class="ya-popover-content-link">$[properties.url]</div>
</div>
`;
