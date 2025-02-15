<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Support\Str;

class PostResource extends JsonResource
{
    public bool $preserveKeys = true;

    /** @return non-empty-array<'html'|'id'|'markdown'|'published'|'title', mixed> */
    public function toArray(Request $request): array
    {
        $postBody = [];

        if ($request->has('include_body')) {
            $returnMarkdown = ['markdown' => $this->markdown];
            $returnHtml = ['html' => $this->html];

            $postBody = match (Str::lower($request->include_body)) {
                'markdown' => $returnMarkdown,
                'md' => $returnMarkdown,
                'html' => $returnHtml,
                default => [...$returnMarkdown, ...$returnHtml],
            };
        }

        $response = [
            'id' => $this->id,
            'title' => $this->title,
            'published' => $this->published_at,
            ...$postBody,
        ];

        return $response;
    }
}
