<li
x-effect="$refs.root.dataset.theme = lightMode ? 'light' : 'dark'"
>
    <button
    class="pico-button-override"
    data-tooltip="Toggle theme"
    data-placement="bottom"
    @mouseenter="$el.focus()"
    @mouseleave="$el.blur()"
    @click="lightMode = !lightMode"
    >
        <x-fas-moon
        x-show="!lightMode"
        />

        <x-fas-sun
        x-show="lightMode"
        fill="#2d3138"
        x-cloak
        />
    </button>
</li>
