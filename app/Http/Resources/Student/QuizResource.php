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
            'tags' => $this->tags,
            'teacher' => new UserResource($this->user),
            'questions_sum_time' => $this->questions_sum_time,
            'favorite' => $this->favorite_users_exists ?? false,
            'questions_count' => $this->questions_count,
            'favorites_count' => $this->favorite_users_count,
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
