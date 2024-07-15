<?php

$possibleThemes = [
    'amber', 'blue', 'cyan', 'fuchsia', 'green', 'grey', 'red', 'jade', 'lime',
    'orange', 'pink', 'pumpkin', 'purple', 'indigo', 'sand', 'slate', 'violet',
    'yellow', 'zinc', 'amber', 'blue', 'cyan', 'jade', 'green', 'grey', 'lime',
    'fuchsia', 'red', 'orange', 'pink', 'pumpkin', 'purple', 'indigo', 'slate',
];

$theme = $possibleThemes[date('d') - 1];

?>

<!DOCTYPE html>
<html
lang="{{ str_replace('_', '-', app()->getLocale()) }}"
>
    <head>
        <meta
        charset="utf-8"
        >

        <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
        >

        <meta
        name="color-scheme"
        content="light dark"
        />

        <link
        rel="stylesheet"
        href="pico/css/pico.classless.{{ $theme }}.min.css"
        />

        <title>
            rqpt's blog
        </title>
    </head>
    <body
    x-data="{hideHeader: @js(request()->is('/'))}"
    >
        <header
        x-cloak
        x-show="!hideHeader"
        >
            <!-- Workaround to get wire:navigate working for links. -->
            <livewire:wire-nav />
            <nav>
                <a
                wire:navigate.hover
                href="/"
                >
                    Home
                </a>
            </nav>
        </header>
        <main>

            {{ $slot }}

        </main>
    </body>
</html>
