<?php

namespace App\Enums;

enum LevelList: int
{
    case Easy = 1;
    case Average = 2;
    case Difficult = 3;

    public function toArray()
    {
        return [
            'name' => $this->name,
            'value' => $this->value,
            'color' => $this->color(),
        ];
    }

    public function color()
    {
        return match ($this) {
            self::Easy => 'success',
            self::Average => 'warning',
            self::Difficult => 'danger',
        };
    }
}
