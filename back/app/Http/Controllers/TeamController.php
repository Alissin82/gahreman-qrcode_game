<?php

namespace App\Http\Controllers;

use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TeamController extends Controller
{    // Get all teams
    public function index()
    {
        return response()->json(Team::all());
    }

    // Get a single team by ID
    public function show($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        return response()->json($team);
    }

    // Create a new team
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'color' => 'nullable|string',
            'content' => 'nullable|string',
            'bio' => 'nullable|string',
            'gender' => 'boolean'
        ]);

        $validated['hash'] = Str::random(16);

        $team = Team::create($validated);

        return response()->json($team, 201);
    }

    // Update an existing team
    public function update(Request $request, $id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'color' => 'nullable|string',
            'content' => 'nullable|string',
            'bio' => 'nullable|string',
            'score' => 'integer',
            'coin' => 'integer',
            'gender' => 'boolean'
        ]);

        $team->update($validated);

        return response()->json($team);
    }

    // Delete a team
    public function destroy($id)
    {
        $team = Team::find($id);

        if (!$team) {
            return response()->json(['message' => 'Team not found'], 404);
        }

        $team->delete();

        return response()->json(['message' => 'Team deleted successfully']);
    }
}
