<?php

namespace App\Markdown;

use League\CommonMark\{
    Environment\EnvironmentBuilderInterface,
    Event\DocumentPreRenderEvent,
    Node\Query,
};
use League\CommonMark\Extension\{
    HeadingPermalink\HeadingPermalink,
    CommonMark\Node\Block\Heading,
    ExtensionInterface,
};

class NodeFocusDisablerExtension implements ExtensionInterface
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

        $headingLinks = (new Query())
            ->where(Query::type(HeadingPermalink::class))
            ->andWhere(Query::hasParent(Query::type(Heading::class)))
            ->findAll($document);

        foreach ($headingLinks as $link) {
            $link->data->append('attributes/tabindex', "-1");
        }
    }
}
