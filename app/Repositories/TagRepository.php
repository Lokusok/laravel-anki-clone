<?php

namespace App\Repositories;

use App\Models\Tag;

class TagRepository
{
    /**
     * @param array<int, string> $tags
     * @return array<int, int>
     */
    public function createFromArrayUnique(array $tags): array
    {
        $tagsIds = collect([]);

        collect($tags)->each(function ($tag) use ($tagsIds) {
            $normalizeTag = strtolower($tag);

            $foundedTag = Tag::query()->where('title', $normalizeTag)->first();

            if (! $foundedTag) {
                $foundedTag = Tag::create(['title' => $normalizeTag]);
            }

            $tagsIds->push($foundedTag->id);
        });

        return $tagsIds->toArray();
    }
}
