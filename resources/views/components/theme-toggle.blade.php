<ul
x-effect="$refs.root.dataset.theme = lightMode ? 'light' : 'dark'"
>
    <li>
        <button
        id="theme-button"
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
</ul>
