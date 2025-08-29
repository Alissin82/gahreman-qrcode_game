<?php

namespace App\Http\Controllers;

use App\Models\Action;
use Illuminate\Http\Request;

class ActionController extends Controller
{
    // لیست همه اکشن‌ها با تمام روابط
    public function index()
    {
        $actions = Action::with([
            'missions' => function ($query) {
                $query->with([
                    'tasks' => function ($taskQuery) {
                        $taskQuery->with([
                            // اینجا هر رابطه‌ای که Task داره اضافه کن
                            'options',
                            'answers'
                        ]);
                    }
                ]);
            },
            'dependency', // پیشنیازها
            'region' // منطقه
        ])->get();

        return response()->json([
            'status' => true,
            'data' => $actions
        ], 200);
    }

    // نمایش اکشن خاص با تمام روابط
    public function show($id)
    {
        $action = Action::with([
            'missions' => function ($query) {
                $query->with([
                    'tasks' => function ($taskQuery) {
                        $taskQuery->with([
                            'options',
                            'answers'
                        ]);
                    }
                ]);
            },
            'dependency',
            'region'
        ])->find($id);

        if (!$action) {
            return response()->json([
                'status' => false,
                'message' => 'Action not found'
            ], 404);
        }

        return response()->json([
            'status' => true,
            'data' => $action
        ], 200);
    }
}
