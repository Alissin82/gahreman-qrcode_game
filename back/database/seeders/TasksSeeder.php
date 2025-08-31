<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Task\Task\Task;

class TasksSeeder extends Seeder
{
    static array $tasks = [];

    public function run(): void
    {
        // skip header
        array_shift(self::$tasks);

        $header = [
            'id',
            'action_id',
            'question',
            'o1',
            'o2',
            'o3',
            'o4',
            'answer',
            'score'
        ];

        foreach (self::$tasks as $task) {
            if ($task[0] === null) {
                continue;
            }

            $task = array_combine($header, $task);
            Task::create([
                'id' => $task['id'],
                'type' => 'question',
                'question' => $task['question'],
                'option1' => $task['o1'],
                'option2' => $task['o2'],
                'option3' => $task['o3'],
                'option4' => $task['o4'],
                'answer' => $task['answer'],
                'score' => $task['score'],
                'mission_id' => $task['action_id'],
                'duration' => 1
            ]);
        }
    }
}
