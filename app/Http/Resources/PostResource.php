<?php

namespace App\Http\Resources;

use Illuminate\Http\{
    Resources\Json\JsonResource,
    Request,
};

class PostResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return parent::toArray($request);
    }
}
