<?php

namespace Database\Seeders;

use App\Models\Mission;
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

        foreach (self::$tasks as $task) {
            if ($task[0] === null) {
                continue;
            }

            $task = array_combine($header, $task);

            // Find or create mission for this action
            $mission = Mission::firstOrCreate(
                ['action_id' => $task['action_id']],
                [
                    'title' => 'Mission for Action ' . $task['action_id'],
                    'score' => 0
                ]
            );

            // Create MCQ model first
            $mcq = MCQ::create([
                'question' => $task['question'],
                'answer' => $task['answer'],
                'options' => [
                    'o1' => $task['o1'],
                    'o2' => $task['o2'],
                    'o3' => $task['o3'],
                    'o4' => $task['o4'],
                ]
            ]);

            // Create Task with proper polymorphic relationship
            Task::create([
                'id' => $task['id'],
                'mission_id' => $mission->id,
                'taskable_type' => MCQ::class,
                'taskable_id' => $mcq->id,
                'type' => TaskType::MCQ->value,
                'score' => $task['score'],
                'duration' => 1,
                'order' => 0,
                'need_review' => false
            ]);
        }
    }
}
