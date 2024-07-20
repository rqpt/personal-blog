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

class TorchlightNodeRendererExtension implements ExtensionInterface, NodeRendererInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addRenderer(FencedCode::class, $this, 100);
    }

    public function render(Node $node, ChildNodeRendererInterface $childRenderer)
    {
        $lang = $node->getInfo();

        if (is_null($lang)) {
            $langAttribute = '';
        } else {
            $langAttribute = " language='$lang'";
        }

        $torchlightOpeningTag = "<pre><x-torchlight-code{$langAttribute}>\n";

        $torchlightClosingTag = '</x-torchlight-code></pre>';

        return BladeManager::renderContent(Blade::render($torchlightOpeningTag.$node->getLiteral().$torchlightClosingTag));
    }
}
