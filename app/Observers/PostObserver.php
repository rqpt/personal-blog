<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\Storage;

class PostObserver
{
    public function updating(Post $post): void
    {
        $originalPost = $post->getOriginal();

        $originalBackupFilename = $post->getBackupFilename(
            $originalPost['title'],
        );

        Storage::delete($originalBackupFilename);
    }

    public function deleted(Post $post): void
    {
        $localBackupFilename = $post->getBackupFilename();

        Storage::delete($localBackupFilename);
    }
}
