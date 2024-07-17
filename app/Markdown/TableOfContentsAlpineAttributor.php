<?php

namespace App\Markdown;

use League\CommonMark\{
    Environment\EnvironmentBuilderInterface,
    Extension\TableOfContents\Node\TableOfContents,
    Extension\ExtensionInterface,
    Event\DocumentPreRenderEvent,
    Node\Query,
};

class TableOfContentsAlpineAttributor implements ExtensionInterface
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

        $tableOfContents?->data->append('attributes/x-ref', "toc");
    }
}
