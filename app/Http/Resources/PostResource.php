<?php

namespace App\Http\Resources;

use Illuminate\Support\Str;
use Illuminate\Http\{
    Resources\Json\JsonResource,
    Request,
};

class PostResource extends JsonResource
{
    public $preserveKeys = true;

    public function toArray(Request $request): array
    {
        $response = [
            'id' => $this->id,
            'title' => $this->title,
            'status' => $this->status->forHumans(),
        ];

        if ($request->has('include_body')) {
            $returnMarkdown = ['markdown' => $this->markdown];
            $returnHtml = ['html' => $this->html];

            $response[] = match (Str::lower($request->include_body)) {
                'markdown' => $returnMarkdown,
                'md' => $returnMarkdown,
                'html' => $returnHtml,
                default => [...$returnMarkdown, ...$returnHtml],
            };
        }

        return $response;
    }
}
