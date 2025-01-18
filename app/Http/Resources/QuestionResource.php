<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuestionResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'front' => $this->front,
            'back' => $this->back,
            'deck_id' => $this->deck_id,
            'tags' => TagResource::collection($this->tags),
            'when_ask' => $this->when_ask->format('d.m.Y'),
            'created_at' => $this->created_at->format('d.m.Y'),
        ];
    }
}
