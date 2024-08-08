<x-layout.app>
    <header
    :id="smallScreen && 'responsive-nav'"
    >
        <nav>
            <ul>
            </ul>

            <ul>
                <li>
                    <a
                    wire:navigate.hover
                    @mouseenter="$el.focus()"
                    @mouseleave="$el.blur()"
                    href="/"
                    data-tooltip="Home"
                    data-placement="left"
                    >
                        <x-fas-house />
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
