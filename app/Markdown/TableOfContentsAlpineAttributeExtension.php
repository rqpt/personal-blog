<?php

namespace App\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentPreRenderEvent;
use League\CommonMark\Extension\CommonMark\Node\Inline\Link;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Extension\TableOfContents\Node\TableOfContents;
use League\CommonMark\Node\Query;

class TableOfContentsAlpineAttributeExtension implements ExtensionInterface
{
    public function register(EnvironmentBuilderInterface $environment): void
    {
        $environment->addEventListener(
            DocumentPreRenderEvent::class,
            [$this, 'onDocumentRendered'],
        );
    }

    public function onDocumentRendered(DocumentPreRenderEvent $event): void
    {
        $document = $event->getDocument();

        $tableOfContents = (new Query())
            ->where(Query::type(TableOfContents::class))
            ->findOne($document);

        $tableOfContents?->data->set('attributes/x-ref', 'toc');

        $links = (new Query())
            ->where(Query::type(Link::class))
            ->findAll($document);

        foreach ($links as $link) {
            $link->data->set('attributes/@mouseenter', '$focus.focus($el)');
        }
    }
}
