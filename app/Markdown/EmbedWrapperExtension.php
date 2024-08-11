<?php

namespace App\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\Embed\Embed;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use League\CommonMark\Util\HtmlElement;
use Illuminate\Support\Str;

class EmbedWrapperExtension implements ExtensionInterface, NodeRendererInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(Embed::class, $this, 100);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        $iframeElement = $node->getEmbedCode();

        $titlePattern = '/title="([^"]*)"/';

        preg_match($titlePattern, $iframeElement, $matches);

        $title = $matches[1];
        $videoid = Str::after($node->getUrl(), '=');

        $span = new HtmlElement('span', ['class' => 'lyt-visually-hidden'], "Play Video: {$title}");

        $anchor = new HtmlElement('a', ['href' => "https://youtube.com/watch?v={$videoid}", 'class' => 'lty-playbtn' ], $span);

        $liteYoutube = new HtmlElement('lite-youtube', [
            ...compact('videoid', 'title'),
            'style' => "background-image: url('https://i.ytimg.com/vi/{$videoid}/hqdefault.jpg');"],
            $anchor
        );

        return $liteYoutube;
    }
}
