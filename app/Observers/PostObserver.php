<?php

namespace App\Observers;

use App\Models\Post;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    public function updated(Post $post): void
    {
        $originalPost = $post->getOriginal();

        $postWasRenamed = $post->wasChanged('title');
        $postStatusWasChanged = $post->wasChanged('published');

        $published = false;

        if ($postStatusWasChanged) {
            $published = $post->published;
        }

        if ($postWasRenamed) {
            $this->renameOriginalFiles($originalPost['title'], $post->title);
        }

        $draftFile = "{$post->title}.md";
        $publishedFile = "{$post->title}.html";

        if ($postStatusWasChanged && $published) {
            $markdown = Storage::disk('drafts')->get($draftFile);
            $html = Markdown::convert($markdown)->getContent();

            Storage::disk('published')->put($publishedFile, $html);
        }

        if ($postStatusWasChanged && !$published) {
            Storage::disk('published')->delete($publishedFile);
        }
    }

    public function deleted(Post $post): void
    {
        $draftFile = "{$post->title}.md";
        $publishedFile = "{$post->title}.html";

        Storage::disk('drafts')->delete($draftFile);
        Storage::disk('published')->delete($publishedFile);
    }

    private function renameOriginalFiles(
        string $originalTitle,
        string $newTitle,
    ): void {
        $originalDraftFile = "{$originalTitle}.md";
        $newDraftFile = "{$newTitle}.md";

        Storage::disk('drafts')
            ->move($originalDraftFile, $newDraftFile);

        $originalPublishedFile = "{$originalTitle}.html";
        $newPublishedFile = "{$newTitle}.html";

        Storage::disk('published')
            ->move($originalPublishedFile, $newPublishedFile);
    }
}
