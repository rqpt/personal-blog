<?php

namespace App\Markdown;

use Illuminate\Support\Facades\Blade;
use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Extension\CommonMark\Node\Block\FencedCode;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Node\Node;
use League\CommonMark\Renderer\ChildNodeRendererInterface;
use League\CommonMark\Renderer\NodeRendererInterface;
use Torchlight\Blade\BladeManager;

class TorchlightRendererExtension implements ExtensionInterface, NodeRendererInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 90);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        $language = $node->getInfo() ?? '';

        $languageAttribute = " language=$language";

        $torchlightOpeningTag = "<pre><x-torchlight-code{$languageAttribute}>\n";

        $torchlightClosingTag = '</x-torchlight-code></pre>';

        return BladeManager::renderContent(Blade::render($torchlightOpeningTag.$node->getLiteral().$torchlightClosingTag));
    }
}
