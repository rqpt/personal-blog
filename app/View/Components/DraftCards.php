<?php

namespace App\View\Components;

use App\Models\Post;
use Closure;
use Illuminate\Contracts\View\View;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\View\Component;

class DraftCards extends Component
{
    public Collection $posts;

    public function __construct()
    {
        $this->posts = Post::drafts()
            ->threeMostRecent()
            ->get();
    }

    public function render(): View|Closure|string
    {
        return view('components.draft-cards');
    }
}
