@extends('layouts.main-layout')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Створення користувачів</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Головна</a></li>
                            <li class="breadcrumb-item active">створення користувачів</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Створити користувача</h3>
                </div>

                <form method="post" action="{{route('store')}}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Ім'я</label>
                            <input type="name" class="form-control" id="name" placeholder="Ім'я"
                                   name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Пароль</label>
                            <input type="password" class="form-control" id="password" placeholder="Пароль"
                                   name="password" required>
                        </div>
                        <div class="form-group">
                            <label>Роль</label>
                            <select class="form-control" id="role" name="role" onchange="checkRole()">
                                @foreach($roles as $role)
                                    <option value="{{$role->id}}">{{$role->name}}</option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Деск</label>
                            <input type="hidden" name="desk_id" value="{{ Auth::user()->desk_id }}">
                            <select id="deskFilter" name="desk_id"
                                    class="form-control custom-select filter-select" {{ !Auth::user()->hasRole('super-admin') ? 'disabled' : '' }}>
                                @foreach($desks as $desk)
                                    <option
                                        value="{{ $desk->desk_id }}"
                                        {{ (Auth::user()->desk_id == $desk->desk_id) ? 'selected' : '' }}>
                                        {{ $desk->desk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Команда</label>
                            <input type="hidden" name="team_id" value="{{ Auth::user()->team_id }}">
                            <select id="teamFilter" name="team_id" class="form-control custom-select filter-select">
                                <option value="">Немає</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->team_id }}"
                                        {{ (Auth::user()->team_id == $team->team_id && (Auth::user()->hasRole('teamlead') || Auth::user()->hasRole('retention_teamlead'))) ? 'selected' : '' }}>
                                        {{ $team->team }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    @can('set permissions')
                        <div class="form-group ml-4">
                            <label>Права користувача</label>
                            <div class="row mt-3 mb-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="selectAll">
                                    <label for="selectAll" class="custom-control-label">Вибрати всі</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createLead"
                                           name="permissions[]"
                                           value="create lead">
                                    <label for="createLead" class="custom-control-label">Створення ліда</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteLead"
                                           name="permissions[]"
                                           value="delete lead">
                                    <label for="deleteLead" class="custom-control-label">Видалення ліда</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="assignLead"
                                           name="permissions[]"
                                           value="assign lead">
                                    <label for="assignLead" class="custom-control-label">Призначення ліда</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="importLeads"
                                           name="permissions[]"
                                           value="import leads">
                                    <label for="importLeads" class="custom-control-label">Імпорт ліда</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllLeads"
                                           name="permissions[]"
                                           value="view all leads">
                                    <label for="viewAllLeads" class="custom-control-label">Бачити всі ліди</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteComments"
                                           name="permissions[]"
                                           value="delete comments">
                                    <label for="deleteComments" class="custom-control-label">Видалення коментарів</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createUser"
                                           name="permissions[]"
                                           value="create user">
                                    <label for="createUser" class="custom-control-label">Створення користувачів</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteUser"
                                           name="permissions[]"
                                           value="delete user">
                                    <label for="deleteUser" class="custom-control-label">Видалення користувачів</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllUsers"
                                           name="permissions[]"
                                           value="view all users">
                                    <label for="viewAllUsers" class="custom-control-label">Бачити всіх користувачів</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="editingUser"
                                           name="permissions[]"
                                           value="editing user">
                                    <label for="editingUser" class="custom-control-label">Редагування користувачів</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="setPermissions"
                                           name="permissions[]"
                                           value="set permissions">
                                    <label for="setPermissions" class="custom-control-label">Встановлення дозволів</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createDesk"
                                           name="permissions[]"
                                           value="create desk">
                                    <label for="createDesk" class="custom-control-label">Створення деска</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteDesk"
                                           name="permissions[]"
                                           value="delete desk">
                                    <label for="deleteDesk" class="custom-control-label">Видалення деска</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllDesks"
                                           name="permissions[]"
                                           value="view all desks">
                                    <label for="viewAllDesks" class="custom-control-label">Бачити всі дески</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createCountry"
                                           name="permissions[]"
                                           value="create country">
                                    <label for="createCountry" class="custom-control-label">Створення країни</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteCountry"
                                           name="permissions[]"
                                           value="delete country">
                                    <label for="deleteCountry" class="custom-control-label">Видалення країни</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllCountries"
                                           name="permissions[]"
                                           value="view all countries">
                                    <label for="viewAllCountries" class="custom-control-label">Бачити всі країни</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createStatus"
                                           name="permissions[]"
                                           value="create status">
                                    <label for="createStatus" class="custom-control-label">Створення статусу</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteStatus"
                                           name="permissions[]"
                                           value="delete status">
                                    <label for="deleteStatus" class="custom-control-label">Видалення статусу</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="changeColor"
                                           name="permissions[]"
                                           value="change color">
                                    <label for="changeColor" class="custom-control-label">Змінювати колір статусу</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllStatuses"
                                           name="permissions[]"
                                           value="view all statuses">
                                    <label for="viewAllStatuses" class="custom-control-label">Бачити всі статуси</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createTeam"
                                           name="permissions[]"
                                           value="create team">
                                    <label for="createTeam" class="custom-control-label">Створення команди</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteTeam"
                                           name="permissions[]"
                                           value="delete team">
                                    <label for="deleteTeam" class="custom-control-label">Видалення команди</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllTeams"
                                           name="permissions[]"
                                           value="view all teams">
                                    <label for="viewAllTeams" class="custom-control-label">Бачити всі команди</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="seeUsersComments"
                                           name="permissions[]"
                                           value="see users comments">
                                    <label for="seeUsersComments" class="custom-control-label">Бачити коментарі ліда</label>
                                </div>
                            </div>
                            <hr>
                        </div>
                    @endcan
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Створити</button>
                    </div>
                </form>
            </div>
        </section>
    </div>
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
        </div>
    </footer>
    @push('scripts')
        <script>
            document.getElementById('selectAll').addEventListener('change', function () {
                var checkboxes = document.querySelectorAll('input[type="checkbox"]');
                for (var checkbox of checkboxes) {
                    checkbox.checked = this.checked;
                }
            });

            function checkRole() {
                var role = document.getElementById('role').value;
                var teamFilter = document.getElementById('teamFilter');

                if (role == '6') {
                    teamFilter.disabled = true;
                    teamFilter.value = '';
                } else {
                    teamFilter.disabled = false;
                }
            }

            window.onload = checkRole;
        </script>
    @endpush
@endsection
