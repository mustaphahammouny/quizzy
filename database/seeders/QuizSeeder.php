<?php

namespace Database\Seeders;

use App\Enums\LevelList;
use App\Enums\QuestionTypeList;
use App\Models\Quiz;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuizSeeder extends Seeder
{
    private $quizzes = [
        [
            'name' => 'html/css',
            'image' => 'html-css.png',
            'tags' => ['web', 'development', 'frontend', 'html', 'css'],
            'questions' => [
                [
                    'question' => 'Which of the following tags is used to create an unordered list in HTML?',
                    'answers' => [
                        ['answer' => '<ol>', 'correct' => false],
                        ['answer' => '<ul>', 'correct' => true],
                        ['answer' => '<li>', 'correct' => false],
                        ['answer' => '<list>', 'correct' => false],
                    ],
                ],
                [
                    'question' => 'What does CSS stand for?',
                    'answers' => [
                        ['answer' => 'Counter Style Sheets', 'correct' => false],
                        ['answer' => 'Computer Style Sheets', 'correct' => false],
                        ['answer' => 'Cascading Style Sheets', 'correct' => true],
                        ['answer' => 'Creative Style Sheets', 'correct' => false],
                    ],
                ],
                [
                    'question' => 'Which property is used to change the text color in CSS?',
                    'answers' => [
                        ['answer' => 'color', 'correct' => true],
                        ['answer' => 'text-color', 'correct' => false],
                        ['answer' => 'font-color', 'correct' => false],
                        ['answer' => 'text-style', 'correct' => false],
                    ],
                ],
                [
                    'question' => 'What is the purpose of the HTML "doctype" declaration?',
                    'answers' => [
                        ['answer' => 'To define the document type and version of HTML', 'correct' => true],
                        ['answer' => 'To create a link to an external stylesheet', 'correct' => false],
                        ['answer' => 'To specify the character encoding of the HTML document', 'correct' => true],
                        ['answer' => 'To declare a JavaScript function', 'correct' => false],
                    ],
                ],
            ],
        ],
        [
            'name' => 'javascript',
            'image' => 'javascript.png',
            'tags' => ['web', 'development', 'frontend', 'javascript', 'ES6'],
            'questions' => [
                [
                    'question' => 'Which keyword is used to declare variables in JavaScript?',
                    'answers' => [
                        ['answer' => 'var', 'correct' => true],
                        ['answer' => 'let', 'correct' => true],
                        ['answer' => 'variable', 'correct' => false],
                        ['answer' => 'const', 'correct' => true],
                    ],
                ],
                [
                    'question' => 'What is the purpose of the "addEventListener" method in JavaScript?',
                    'answers' => [
                        ['answer' => 'To add styles to elements', 'correct' => false],
                        ['answer' => 'To attach an event handler to an element', 'correct' => true],
                        ['answer' => 'To create new HTML elements', 'correct' => false],
                        ['answer' => 'To remove elements from the DOM', 'correct' => false],
                    ],
                ],
                [
                    'question' => 'Which of the following is a truthy value in JavaScript?',
                    'answers' => [
                        ['answer' => '0', 'correct' => true],
                        ['answer' => 'null', 'correct' => false],
                        ['answer' => 'undefined', 'correct' => false],
                        ['answer' => 'true', 'correct' => true],
                    ],
                ],
                [
                    'question' => 'What is the purpose of the "this" keyword in JavaScript?',
                    'answers' => [
                        ['answer' => 'To refer to the current object', 'correct' => true],
                        ['answer' => 'To create a new object', 'correct' => true],
                        ['answer' => 'To delete an object property', 'correct' => false],
                        ['answer' => 'To define a function', 'correct' => false],
                    ],
                ],
            ],
        ],
        [
            'name' => 'php',
            'image' => 'php.png',
            'tags' => ['web', 'development', 'backend', 'php', 'OOP'],
            'questions' => [
                [
                    'question' => 'What does PHP stand for?',
                    'answers' => [
                        ['answer' => 'Personal Home Page', 'correct' => false],
                        ['answer' => 'Hypertext Preprocessor', 'correct' => true],
                        ['answer' => 'Programming Hyper Processor', 'correct' => false],
                        ['answer' => 'Pretext Hypertext Processor', 'correct' => false],
                    ],
                ],
                [
                    'question' => 'In PHP, what function is used to read a file?',
                    'answers' => [
                        ['answer' => 'file_open()', 'correct' => false],
                        ['answer' => 'open_file()', 'correct' => false],
                        ['answer' => 'readfile()', 'correct' => true],
                        ['answer' => 'read_file()', 'correct' => false],
                    ],
                ],
                [
                    'question' => 'Which of the following data types is NOT supported in PHP?',
                    'answers' => [
                        ['answer' => 'integer', 'correct' => false],
                        ['answer' => 'float', 'correct' => false],
                        ['answer' => 'boolean', 'correct' => false],
                        ['answer' => 'char', 'correct' => true],
                    ],
                ],
                [
                    'question' => 'What is the purpose of the "include" statement in PHP?',
                    'answers' => [
                        ['answer' => 'To include an external PHP file', 'correct' => true],
                        ['answer' => 'To create a new variable', 'correct' => false],
                        ['answer' => 'To remove a file', 'correct' => false],
                        ['answer' => 'To define a function', 'correct' => true],
                    ],
                ],
            ],
        ],
    ];

    public function run(): void
    {
        $teacher = User::role('teacher')->first();
        $levels = LevelList::cases();

        foreach ($this->quizzes as $quiz) {
            $quizModel = Quiz::create([
                'user_id' => $teacher->id,
                'name' => $quiz['name'],
                'active' => true,
                'level' => $levels[array_rand($levels)]->value,
                'tags' => $quiz['tags'],
            ]);

            $quizModel->addMedia(resource_path("images/quizzes/{$quiz['image']}"))
                ->preservingOriginal()
                ->toMediaCollection();

            foreach ($quiz['questions'] as $question) {
                $correctAnswersCount = collect($question['answers'])->filter(fn ($answer) => $answer['correct']);

                $questionModel = $quizModel->questions()->create([
                    'type' => $correctAnswersCount->count() > 1 ? QuestionTypeList::Checkbox->value : QuestionTypeList::Radio->value,
                    'time' => random_int(5, 10),
                    'question' => $question['question'],
                ]);

                $questionModel->answers()->createMany($question['answers']);
            }
        }
    }
}
