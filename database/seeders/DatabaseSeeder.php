<?php

namespace Database\Seeders;

use App\Models\Post;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        Post::factory()->create();

        Post::factory()->published()->create();
        Post::factory()->published()->withTableOfContents()->create();
        Post::factory()->published()->withTableOfContents()->withAnEmbeddedVideo()->create();

        Post::factory()->drafted()->create();
        Post::factory()->drafted()->withTableOfContents()->create();
        Post::factory()->drafted()->withTableOfContents()->withAnEmbeddedVideo()->create();
    }
}
