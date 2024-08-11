<?php

namespace App\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\Embed\Embed;
use League\CommonMark\Extension\Embed\EmbedRenderer;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Renderer\HtmlDecorator;

class EmbedWrapperExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(Embed::class, new HtmlDecorator(new EmbedRenderer, 'div', ['class' => 'embedded-youtube-iframe']), 100);
    }
}
