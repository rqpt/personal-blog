<?php

namespace App\Observers;

use App\Models\Post;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    public function created(Post $post): void
    {
        $markdown = $post->body;

        $post->body = Markdown::convert($markdown)->getContent();

        Storage::disk('backup')->put(
            $post->getBackupFilename(),
            $markdown,
        );
    }

    public function updating(Post $post): void
    {
        $originalPost = $post->getOriginal();

        $localBackupFilename = $post->getBackupFilename(
            $originalPost['title'],
        );

        $originalMarkdown = $post->body;

        $html = Markdown::convert($post->body)->getContent();

        $post->body = $html;

        Storage::disk('backup')->put(
            $localBackupFilename,
            $originalMarkdown,
        );

        $postWasRenamed = $post->wasChanged('title');

        if ($postWasRenamed) {
            $newLocalBackupFilename = $post->getBackupFilename();

            Storage::disk('backup')->move(
                $localBackupFilename,
                $newLocalBackupFilename,
            );
        }
    }

    public function deleted(Post $post): void
    {
        $localBackupFilename = $post->getBackupFilename();

        Storage::disk('backup')->delete($localBackupFilename);
    }

    private function backupOriginalAndStoreHtml(
        string $localBackupFilename,
        Post $post,
    ): void {
        Storage::disk('backup')->put(
            $localBackupFilename,
            $post->body,
        );

        $post->body = Markdown::convert($post->body)->getContent();
    }
}
