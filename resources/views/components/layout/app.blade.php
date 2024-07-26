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

/*
 Margin and rounding are personal preferences,
 overflow-x-auto is recommended.
*/

pre {
    border-radius: 0.25rem;
    margin-top: 1rem;
    margin-bottom: 1rem;
    overflow-x: auto;
}

/*
 Add some vertical padding and expand the width
 to fill its container. The horizontal padding
 comes at the line level so that background
 colors extend edge to edge.
*/

pre code.torchlight {
    display: block;
    min-width: -webkit-max-content;
    min-width: -moz-max-content;
    min-width: max-content;
    padding-top: 1rem;
    padding-bottom: 1rem;
}

/*
 Horizontal line padding to match the vertical
 padding from the code block above.
*/

pre code.torchlight .line {
    padding-left: 1rem;
    padding-right: 1rem;
}

/*
 Push the code away from the line numbers and
 summary caret indicators.
*/

pre code.torchlight .line-number,
pre code.torchlight .summary-caret {
    margin-right: 1rem;
}

/*
 Show only the current themes code block.
*/

@media (prefers-color-scheme: light) {
    pre code[data-theme="light"] {
        display: block;
    }

    pre code[data-theme="dark"] {
        display: none;
    }
}

@media (prefers-color-scheme: dark) {
    pre code[data-theme="light"] {
        display: none;
    }

    pre code[data-theme="dark"] {
        display: block;
    }
}

/*
 Below styles are for the collapsing feature.
*/

.torchlight summary:focus {
    outline: none;
}

/* Hide the default markers, as we provide our own */

.torchlight details > summary::marker,
.torchlight details > summary::-webkit-details-marker {
    display: none;
}

.torchlight details .summary-caret::after {
    pointer-events: none;
}

/* Add spaces to keep everything aligned */

.torchlight .summary-caret-empty::after,
.torchlight details .summary-caret-middle::after,
.torchlight details .summary-caret-end::after {
    content: " ";
}

/* Show a minus sign when the block is open. */

.torchlight details[open] .summary-caret-start::after {
    content: "-";
}

/* And a plus sign when the block is closed. */

.torchlight details:not([open]) .summary-caret-start::after {
    content: "+";
}

/* Hide the [...] indicator when open. */

.torchlight details[open] .summary-hide-when-open {
    display: none;
}

/* Show the [...] indicator when closed. */

.torchlight details:not([open]) .summary-hide-when-open {
    display: initial;
}

/*
  Blur and dim the lines that don't have the `.line-focus` class,
  but are within a code block that contains any focus lines.
*/

.torchlight.has-focus-lines .line:not(.line-focus) {
    transition: filter 0.35s, opacity 0.35s;
    filter: blur(.095rem);
    opacity: .65;
}

/*
  When the code block is hovered, bring all the lines into focus.
*/

.torchlight.has-focus-lines:hover .line:not(.line-focus) {
    filter: blur(0px);
    opacity: 1;
}

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
