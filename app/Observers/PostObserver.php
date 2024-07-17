<?php

namespace App\Observers;

use App\Models\Post;
use Illuminate\Support\Facades\{DB, Storage};

class PostObserver
{
    public function updating(Post $post): void
    {
        $oldTitle = DB::table('posts')
            ->find($post->id, 'title')
            ->title;

        if ($post->title != $oldTitle) {
            $this->removeOldFiles($oldTitle);
        }

        if (!$post->published) {
            $postsPath = 'posts';
            $publishedPath = "$postsPath/published/{$post->title}.html";

            if (Storage::exists($publishedPath)) {
                Storage::delete($publishedPath);
            }
        }
    }

    public function deleted(Post $post): void
    {
        $this->removeOldFiles($post->title);
    }

    private function removeOldFiles(string $title): void
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
}
