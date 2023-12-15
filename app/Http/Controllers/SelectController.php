<?php

namespace App\Http\Controllers;

use App\Enums\LevelList;
use App\Enums\QuestionTypeList;
use App\Http\Resources\SelectResource;

class SelectController extends Controller
{
    public function levels()
    {
        $levels = LevelList::cases();

        return SelectResource::collection($levels);
    }

    public function questionTypes()
    {
        $questionTypes = QuestionTypeList::cases();

        return SelectResource::collection($questionTypes);
    }
}
