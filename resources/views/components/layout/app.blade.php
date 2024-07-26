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
        href="pico/css/pico.classless.cyan.min.css"
        />

        <style>
            [x-cloak] { display: none !important; }

/* Scrollbar  */

:root
{
    --scrollbarBG: #0097A7;
    --thumbBG: #0097A7;
}

::-webkit-scrollbar
{
    width: 0.5em;
}

::-webkit-scrollbar-track
{
    background: var(--scrollbarBG);
}

::-webkit-scrollbar-thumb
{
    background: var(--thumbBG);
}

@supports (scrollbar-color: red blue)
{
    *
    {
        scrollbar-color: var(--scrollbarBG) transparent;
    }
}


/* Heading links */

.heading-permalink
{
    font-size: .8em;
    vertical-align: super;
    text-decoration: none;
    color: transparent;
}

h1:hover .heading-permalink,
h2:hover .heading-permalink,
h3:hover .heading-permalink,
h4:hover .heading-permalink,
h5:hover .heading-permalink,
h6:hover .heading-permalink,
.heading-permalink:hover,
.heading-permalink:focus
{
    text-decoration: none;
    color: #777;
}

/* Pico overrides */

#search
{
    margin-bottom: 0;
}

@media (max-width: 640px)
{
    #search
    {
        position: fixed;
        bottom: 3rem;
        left: 50%;
        transform: translateX(-50%);
        width: 95%;
        max-width: 700px;
    }
}

#theme-toggle
{
    cursor: pointer;
}

.pico-button-override
{
    background: transparent;
    border: transparent;
}

/* Embedded overrides */

.embedded-content
{
    display: flex;
    margin-top: 2rem;
    justify-content: center;
}

/* In Rainbows */

#in-rainbows
{
    width: 24px;
    aspect-ratio: 1 / 1;
    border-radius: 100%;
}
        </style>

        @vite(['resources/css/app.css', 'resources/css/torchlight.css'])

        <title>
            rqpt's blog
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

        {{ $slot }}

         <svg
         hidden
         class="hidden"
         >
            @stack('bladeicons')
        </svg>
    </body>
</html>
