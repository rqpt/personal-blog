<?php

declare(strict_types=1);

use League\CommonMark\Extension\CommonMark\Node\Inline\Link;

return [

    'views' => false,

    'extensions' => [
        League\CommonMark\Extension\DefaultAttributes\DefaultAttributesExtension::class,
        League\CommonMark\Extension\HeadingPermalink\HeadingPermalinkExtension::class,
        League\CommonMark\Extension\TableOfContents\TableOfContentsExtension::class,
        League\CommonMark\Extension\CommonMark\CommonMarkCoreExtension::class,
        League\CommonMark\Extension\FrontMatter\FrontMatterExtension::class,
        League\CommonMark\Extension\GithubFlavoredMarkdownExtension::class,
        League\CommonMark\Extension\Autolink\AutolinkExtension::class,
        League\CommonMark\Extension\Embed\EmbedExtension::class,
        App\Markdown\TableOfContentsAlpineAttributeExtension::class,
        App\Markdown\TorchlightRendererExtension::class,
        App\Markdown\DecorateEmbedsExtension::class,
        App\Markdown\CodeRendererExtension::class,
    ],

    'default_attributes' => [
        Link::class => [
            '@mouseenter' => '$el.focus()',
            'x-intersect:enter' => '!atTopOfPage && $el.focus({ preventScroll: true })',
            'x-intersect:leave' => '$el.blur()',
        ],
    ],

    'heading_permalink' => [
        'insert' => 'after',
        'symbol' => '¶',
    ],

    'table_of_contents' => [
        'min_heading_level' => 2,
        'max_heading_level' => 3,
    ],

    'renderer' => [
        'block_separator' => "\n",
        'inner_separator' => "\n",
        'soft_break' => "\n",
    ],

    'commonmark' => [
        'enable_em' => true,
        'enable_strong' => true,
        'use_asterisk' => true,
        'use_underscore' => true,
        'unordered_list_markers' => ['-', '+', '*'],
    ],

    'html_input' => 'strip',

    'allow_unsafe_links' => true,

    'max_nesting_level' => PHP_INT_MAX,

    'slug_normalizer' => [
        'max_length' => 255,
        'unique' => 'document',
    ],

];
