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
        href="https://cdn.jsdelivr.net/npm/@picocss/pico@2/css/pico.classless.{{ $theme }}.min.css"
        />

        <style>
            [x-cloak] { display: none !important; }
        </style>

        @vite(['resources/css/app.css', 'resources/css/torchlight.css'])

        <title>
            rqpt's blog
        </title>
    </head>
    <livewire:wire-nav />

    <body>

        {{ $slot }}

    </body>
</html>
