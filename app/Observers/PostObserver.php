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

        $postsPath = 'posts';
        $draftPath = "$postsPath/drafts/{$post->title}.md";
        $publishedPath = "$postsPath/published/{$post->title}.html";

        if ($postStatusWasChanged && $published) {
            $markdown = Storage::get($draftPath);

            $html = Markdown::convert($markdown)->getContent();

            Storage::put($publishedPath, $html);
        }

        if ($postStatusWasChanged && !$published) {
            if (Storage::exists($publishedPath)) {
                Storage::delete($publishedPath);
            }
        }
    }

    public function deleted(Post $post): void
    {
        $this->removeFiles($post->title);
    }

    private function removeFiles(string $title): void
    {
        $postsPath = 'posts';

        $draftPath = "$postsPath/drafts/{$title}.md";

        if (Storage::exists($draftPath)) {
            Storage::delete($draftPath);
        }

        $publishedPath = "$postsPath/published/{$title}.html";

        if (Storage::exists($publishedPath)) {
            Storage::delete($publishedPath);
        }
    }

    private function renameOriginalFiles(
        string $originalTitle,
        string $newTitle,
    ): void {
        $postsPath = 'posts';

        $originalDraftPath = "$postsPath/drafts/{$originalTitle}.md";
        $newDraftPath = "$postsPath/drafts/{$newTitle}.md";

        if (Storage::exists($originalDraftPath)) {
            Storage::move($originalDraftPath, $newDraftPath);
        }

        $originalPublishedPath = "$postsPath/published/{$originalTitle}.html";
        $newPublishedPath = "$postsPath/published/{$newTitle}.html";

        if (Storage::exists($originalPublishedPath)) {
            Storage::move($originalPublishedPath, $newPublishedPath);
        }
    }
}
