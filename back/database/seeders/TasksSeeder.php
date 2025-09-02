<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Task\Enum\TaskType;
use Modules\Task\Models\Task;
use Modules\MCQ\Models\MCQ;

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

        $order = 1;
        foreach (self::$tasks as $row) {
            if ($row[0] === null) {
                continue;
            }

            $row = array_combine($header, $row);

            // Create MCQ model first
            $mcq = MCQ::create([
                'question' => $row['question'],
                'answer' => $row['answer'],
                'options' => [
                    'o1' => $row['o1'],
                    'o2' => $row['o2'],
                    'o3' => $row['o3'],
                    'o4' => $row['o4'],
                ]
            ]);

            // Create Task with proper polymorphic relationship
            Task::create([
                'action_id' => $row['action_id'],
                'taskable_type' => MCQ::class,
                'taskable_id' => $mcq->id,
                'type' => TaskType::MCQ->value,
                'score' => $row['score'],
                'duration' => 1, // minute
                'need_review' => false
            ]);

            $order++;
        }
    }
}
