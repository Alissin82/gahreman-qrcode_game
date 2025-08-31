<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Task\Enum\TaskType;
use Modules\Task\Models\Task;

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
                'type' => TaskType::MCQ->value,
                'question' => $task['question'],
                'answer' => $task['answer'],
                'score' => $task['score'],
                'action_id' => $task['action_id'],
                'duration' => 1
            ]);
        }
    }
}
