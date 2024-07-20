<?php

return [

    'random_markdown' => [
        'url' => 'https://jaspervdj.be/lorem-markdownum/markdown.txt',
    ],

    'openai' => [
        'url' => 'https://api.openai.com/v1/chat/completions',
        'api_key' => env('OPENAI_TOKEN'),
    ],

];
