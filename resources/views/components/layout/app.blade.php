<!DOCTYPE html>
<html
lang="{{ str_replace('_', '-', app()->getLocale()) }}"
x-data="{ lightMode: window.matchMedia('(prefers-color-scheme: light)').matches }"
x-ref="root"
>
    <head>
        <meta
        charset="utf-8"
        >

        <x-seo::meta />

        <meta
        name="viewport"
        content="width=device-width, initial-scale=1"
        >

        <meta
        name="color-scheme"
        content="light dark"
        />

        <link
        rel="apple-touch-icon"
        sizes="180x180"
        href="/apple-touch-icon.png"
        >

        <link
        rel="icon"
        type="image/png"
        sizes="32x32"
        href="/favicon-32x32.png"
        >

        <link
        rel="icon"
        type="image/png"
        sizes="16x16"
        href="/favicon-16x16.png"
        >

        <link
        rel="manifest"
        href="/site.webmanifest"
        >

        <link
        rel="stylesheet"
        href="/css/pico.classless.cyan.min.css"
        />

        @vite(['resources/js/app.js', 'resources/css/app.css', 'resources/css/torchlight.css'])

        <title>
            PE Vermeulen - Software Engineer
        </title>
    </head>
    <livewire:wire-nav />

    <body
    x-data="{
        tocExpanded: false,
        atTopOfPage: true,
        smallScreen: window.matchMedia('(max-width: 640px)').matches
    }"
    {{ $attributes }}
    >
        <div
        id="ascii-grid"
        aria-hidden="true"
        >
            <pre
            class="ascii"
            >
               ______
         _____/------|
        /-----      ||
        ||          ||
        ||          ||
        ||          ||
        ||          ||
        ||       ___||
        ||      /    |
     ___||      \___/
    /    |
    \___/
            </pre>

            <pre
            class="ascii"
            >
         ___________________________________________________________________
        |  ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___________  |
        | | ` | 1 | 2 | 3 | 4 | 5 | 6 | 7 | 8 | 9 | 0 | - | = | Backspace | |
        | |___|___|___|___|___|___|___|___|___|___|___|___|___|___________| |
        |  _______ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ _______  |
        | | Tab   | Q | W | F | P | G | J | L | U | Y | ; | [ | ] |       | |
        | |_______|___|___|___|___|___|___|___|___|___|___|___|___|       | |
        |  _______ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___| Enter | |
        | | Caps  | A | R | S | T | G | M | N | E | I | O | ' | \ |       | |
        | |_______|___|___|___|___|___|___|___|___|___|___|___|___|___    | |
        |  _______ ___ ___ ___ ___ ___ ___ ___ ___ ___ ___ _______ ___|   | |
        | | Shift | X | C | D | V | Z | K | H | , | . | / | Shift | ^ |   | |
        | |_______|___|___|___|___|___|___|___|___|___|___|_______|___|___| |
        |  ______ _____ _____ ___________ _____ _____ ______   ___ ___ ___  |
        | | Ctrl | Win | Alt |   Space   | Alt | Win | Ctrl | | &lt | V | &gt | |
        | |______|_____|_____|___________|_____|_____|______| |___|___|___| |
        |___________________________________________________________________|
            </pre>

            <pre
            class="ascii"
            >
         ________     ________
        /       /    /
       /_______/    /_______
      /            /
     /            /________
            </pre>
        </div>

        {{ $slot }}

         <svg
         hidden
         class="hidden"
         >
            @stack('bladeicons')
        </svg>
    </body>
</html>
