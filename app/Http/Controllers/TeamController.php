<?php

namespace App\Http\Controllers;

use App\Models\Desk;
use Illuminate\Http\Request;
use App\Models\Team;

class TeamController extends Controller
{
    public function showTeams()
    {
        $teams = Team::all();
        $desks = Desk::all();
        return view('super-admin.team', compact('teams','desks'));
    }

    public function createTeam(Request $request)
    {
        $request->validate([
            'team' => 'required|unique:teams',
            'desk_id' => 'required|exists:desks,desk_id',
        ]);

        Team::create([
            'team' => $request->team,
            'desk_id' => $request->desk_id,
        ]);

        return redirect()->back()->with('success', 'Команда успешно создана!');
    }


    public function deleteTeam($teamId)
    {
        $team = Team::find($teamId);

        if ($team) {
            $team->delete();
            return redirect()->back()->with('success', 'Команда успешно удалена!');
        } else {
            return redirect()->back()->with('error', 'Команда не найдена!');
        }
    }
}


