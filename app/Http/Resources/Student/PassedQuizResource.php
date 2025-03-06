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
            'quiz' => $this->whenLoaded('quiz', fn() => QuizResource::make($this->quiz)),
            'items' => $this->whenLoaded('items', fn() => PassedQuizItemResource::collection($this->items)),
            'correct_count' => $this->whenHas('correct_items_count', fn() => $this->correct_items_count),
            'items_count' => $this->whenCounted('items', fn() => $this->items_count),
            'items_sum_time' => $this->whenAggregated('items', 'time', 'sum', fn() => $this->items_sum_time),
            'created_at' => $this->created_at->format('Y-m-d'),
            'updated_at' => $this->updated_at->format('Y-m-d'),
        ];
    }
}
