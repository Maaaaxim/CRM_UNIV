<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Desk;
use App\Models\Lead;
use App\Models\Status;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DistributionController extends Controller
{
    public function leadsDistributionPage(Request $request)
    {
        // Если это POST-запрос, то сохраняем assigned_leads в сессии
        if ($request->isMethod('post')) {
            $leadsArray = $request->input('assigned_leads');
            session(['assigned_leads' => $leadsArray]);
        } else {
            // Если это не POST-запрос, извлекаем данные из сессии
            $leadsArray = session('assigned_leads', []); // Если данных нет, будет использован пустой массив
        }


        $userQuery = User::query();
        $userQuery->where('deleted', false);

        $user = Auth::user();

        if ($user->hasRole('super-admin')) {
            // ничего не фильтруется, так как super-admin может видеть всех
        } elseif ($user->hasRole('head')) {
            $userQuery->where('desk_id', $user->desk_id);
        } elseif ($user->hasRole('retention_teamlead')) {
            $userQuery->where('team_id', $user->team_id);
        } elseif ($user->hasRole('teamlead')) {
            $userQuery->where('team_id', $user->team_id);
        }

        // Фильтрация на основе полученных параметров
        if ($request->has('team_id') && $request->input('team_id') != 0) {
            $userQuery->where('team_id', $request->input('team_id'));
        }

        if ($request->has('desk_id') && $request->input('desk_id') != 0) {
            $userQuery->where('desk_id', $request->input('desk_id'));
        }

        $userQuery->withCount('lead as userLeadsCount'); // Добавлен подсчет лидов

        $users = $userQuery->with(['team', 'desk'])->get();

        // Получение всех необходимых данных для фильтров
        $teams = Team::query();
        $desks = Desk::query();

        if ($user->hasRole('super-admin')) {
            // ничего не фильтруется, так как super-admin может видеть всех
            $teams = $teams->get();
            $desks = $desks->get();
        } elseif ($user->hasRole('head')) {
            $teams = $teams->where('desk_id', $user->desk_id)->get();
            $desks = $desks->where('desk_id', $user->desk_id)->get();
        } elseif ($user->hasRole('retention_teamlead')) {
            $teams = $teams->where('team_id', $user->team_id)->get();
            $desks = $desks->where('desk_id', $user->desk_id)->get();
        } elseif ($user->hasRole('teamlead')) {
            $teams = $teams->where('team_id', $user->team_id)->get();
            $desks = $desks->where('desk_id', $user->desk_id)->get();
        } else {
            // для остальных ролей, если нужно
        }

        return view('super-admin.distribution', compact('users', 'teams', 'desks', 'leadsArray'));
    }


    public function distributionleadsAssign(Request $request)
    {
        $leadIds = $request->input('leads');
        $userLeads = $request->input('user_leads');
        if (!$userLeads) {
            return redirect()->back()->with('errors', 'Вы не выбрали ни одного сейла!');
        }


        $totalAssignedLeads = array_sum($userLeads);


        if ($totalAssignedLeads > count($leadIds)) {
            return redirect()->back()->with('errors', 'Вы неверно распределили лиды, научитесь считать!');
        }
        if (empty($leadIds) || empty($userLeads)) {
            return redirect()->back()->with('errors', 'Лиды или пользователи не выбраны!');
        }

        $assignedLeads = 0;


        $currentUserId = auth()->user()->id;

        foreach ($userLeads as $userId => $leadsCount) {
            $leadsCount = intval($leadsCount);

            for ($i = 0; $i < $leadsCount; $i++) {

                $leadId = $leadIds[$assignedLeads];

                $lead = Lead::find($leadId);
                $originalUserId = $lead->user_id;
                $lead->user_id = $userId;
                $lead->created_by = $currentUserId;
                $lead->user_id_updated_at = now();
                $lead->save();

                if ($originalUserId !== $userId) {
                    $lead->logChange('user_id_changed',$originalUserId, $userId);
                }

                $assignedLeads++;
            }
        }
        return redirect()->route('showLeads')->with('success', 'Лиды успешно распределены!');
    }


}
