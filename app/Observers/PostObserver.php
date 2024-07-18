<?php

namespace App\Observers;

use App\Models\Post;
use GrahamCampbell\Markdown\Facades\Markdown;
use Illuminate\Support\{
    Facades\Storage,
    Str,
};

class PostObserver
{
    public function creating(Post $post): void
    {
        $localBackupFilename = Str::slug($post->title) . '.md';

        Storage::disk('backup')->put(
            $localBackupFilename,
            $post->body,
        );

        $post->body = Markdown::convert($post->body)->getContent();
    }

    public function updating(Post $post): void
    {
        $originalPost = $post->getOriginal();

        $localBackupFilename = Str::slug($originalPost['title']) . '.md';

        $postWasRenamed = $post->wasChanged('title');

        if ($postWasRenamed) {
            $newLocalBackupFilename = Str::slug($post->title) . '.md';

            Storage::disk('backup')->move(
                $localBackupFilename,
                $newLocalBackupFilename,
            );

            $localBackupFilename = $newLocalBackupFilename;
        }

        $postBodyWasChanged = $post->wasChanged('body');

        if ($postBodyWasChanged) {
            Storage::disk('backup')->put(
                $localBackupFilename,
                $post->body,
            );

            $post->body = Markdown::convert($post->body)->getContent();
        }
    }

    public function deleted(Post $post): void
    {
        $localBackupFilename = Str::slug($post->title) . '.md';

        Storage::disk('backup')->delete($localBackupFilename);
    }
}
