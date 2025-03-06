<?php

namespace App\Http\Resources\Student;

use App\Http\Resources\UserResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class QuizResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'name' => $this->name,
            'level' => $this->level->toArray(),
            'image' => $this->getFirstMediaUrl(),
            'tags' => $this->tags,
            'teacher' => $this->whenLoaded('user', fn() => UserResource::make($this->user)),
            'questions_sum_time' => $this->whenAggregated('questions', 'time', 'sum'),
            'favorite' => $this->whenHas('favorite_users_exists', fn() => $this->favorite_users_exists),
            'questions_count' => $this->whenCounted('questions'),
            'favorites_count' => $this->whenCounted('favoriteUsers'),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
