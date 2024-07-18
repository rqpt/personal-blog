<?php

namespace App\Markdown;

use League\CommonMark\Extension\Embed\{Embed, EmbedRenderer};
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\{
    Environment\EnvironmentBuilderInterface,
    Renderer\HtmlDecorator,
};

class EmbedWrapperExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(
            Embed::class,
            new HtmlDecorator(new EmbedRenderer(), 'div', ['tabindex' => '-1']),
        );
    }
}
