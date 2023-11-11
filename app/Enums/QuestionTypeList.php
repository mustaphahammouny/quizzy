<?php

namespace App\Enums;

enum QuestionTypeList: int
{
    case Radio = 1;
    case Checkbox = 2;

    public function toArray()
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
        ];
    }
}
