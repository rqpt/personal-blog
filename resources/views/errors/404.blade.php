<x-layout.app>
    <header>
        <nav>
            <ul>
            </ul>

            <ul>
                <li
                x-data="{ clicked: false }"
                :aria-busy="clicked"
                :aria-label="'Please wait...'"
                >
                    <a
                    x-show="!clicked"
                    wire:navigate.hover
                    @click.capture="clicked = true"
                    @keydown.capture.enter="clicked = true"
                    @mouseenter="$el.focus()"
                    @mouseleave="$el.blur()"
                    href="/"
                    data-tooltip="Home"
                    data-placement="left"
                    >
                        <x-fas-house/>
                    </a>
                </li>
                <x-theme-toggle />
            </ul>
        </nav>
    </header>
    <main>
        <h1>
            Post not found
        </h1>
    </main>
</x-layout.app>
