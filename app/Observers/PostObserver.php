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

        $this->fetchOriginalAndStoreHtml($localBackupFilename, $post);
    }

    public function updating(Post $post): void
    {
        $originalPost = $post->getOriginal();

        $localBackupFilename = $post->getBackupFilename(
            $originalPost['title'],
        );

        $this->fetchOriginalAndStoreHtml($localBackupFilename, $post);

        if ($post->isDirty('title')) {
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

    private function fetchOriginalAndStoreHtml(
        string $localBackupFilename,
        Post $post,
    ): void {
        $markdown = Storage::disk('backup')->get($localBackupFilename);

        $post->body = Markdown::convert($markdown)->getContent();
    }
}
