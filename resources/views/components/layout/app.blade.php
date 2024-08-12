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
        <x-ascii />

        {{ $slot }}

         <svg
         hidden
         class="hidden"
         >
            @stack('bladeicons')
        </svg>
    </body>
</html>
