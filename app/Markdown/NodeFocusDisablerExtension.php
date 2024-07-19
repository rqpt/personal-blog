<?php

namespace App\Markdown;

use League\CommonMark\Environment\EnvironmentBuilderInterface;
use League\CommonMark\Event\DocumentPreRenderEvent;
use League\CommonMark\Extension\CommonMark\Node\Block\Heading;
use League\CommonMark\Extension\ExtensionInterface;
use League\CommonMark\Extension\HeadingPermalink\HeadingPermalink;
use League\CommonMark\Node\Query;

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
            $link->data->append('attributes/tabindex', '-1');
        }
    }
}
