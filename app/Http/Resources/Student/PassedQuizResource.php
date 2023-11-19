<?php

namespace App\Http\Resources\Student;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PassedQuizResource extends JsonResource
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
            'quiz' => new QuizResource($this->quiz),
            'items' => PassedQuizItemResource::collection($this->items),
            'correct_count' => $this->items->where('correct', true)->count(),
            'items_count' => $this->items->count(),
            'items_sum_time' => $this->items->sum('time'),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
