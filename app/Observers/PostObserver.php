<?php

namespace App\Observers;

use App\Models\Post;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    public function creating(Post $post): void
    {
        $localBackupFilename = $post->getBackupFilename();

        $this->backupOriginalAndStoreHtml($post, $localBackupFilename);
    }

    public function updating(Post $post): void
    {
        $originalPost = $post->getOriginal();

        $localBackupFilename = $post->getBackupFilename(
            $originalPost['title'],
        );

        $this->backupOriginalAndStoreHtml($post, $localBackupFilename);

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
        Post $post,
        string $localBackupFilename,
    ): void {
        $markdown = $post->body;

        $post->body = Markdown::convert($markdown)->getContent();

        Storage::disk('backup')->put(
            $localBackupFilename,
            $markdown,
        );
    }
}
