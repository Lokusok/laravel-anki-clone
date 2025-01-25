<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DeckResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'user_id' => $this->user_id,
            'created_at' => $this->created_at->format('d.m.Y'),
            'ask_ready' => $this->questionsAskReady->count(),
            'ask_later' => $this->questionsAskLater->count(),
        ];
    }
}
