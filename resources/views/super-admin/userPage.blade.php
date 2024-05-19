@extends('layouts.main-layout')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Обновление пользователей</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Гланая</a></li>
                            <li class="breadcrumb-item active">обновление пользователей</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Обновить пользователя</h3>
                </div>


                <form method="post" action="{{route('update')}}">

                    @csrf

                    <input type="hidden" name="id" value="{{ $user->id }}">
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Имя</label>

                            {{--                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"--}}
                            {{--                                 style="cursor: pointer"--}}
                            {{--                                 viewBox="0 0 448 512"--}}
                            {{--                                 onclick="copyContent('{{ $user->name }}')"--}}
                            {{--                            >--}}
                            {{--                                <path--}}
                            {{--                                    d="M208 0H332.1c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9V336c0 26.5-21.5 48-48 48H208c-26.5 0-48-21.5-48-48V48c0-26.5 21.5-48 48-48zM48 128h80v64H64V448H256V416h64v48c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V176c0-26.5 21.5-48 48-48z"/>--}}
                            {{--                            </svg>--}}
                            <input type="name" class="form-control" id="name" placeholder="Имя" data-a
                                   name="name" required value="{{$user->name}}">
                        </div>
                        <div class="form-group">
                            <label for="name">Пароль</label>
                            {{--                            <svg xmlns="http://www.w3.org/2000/svg" height="1em"--}}
                            {{--                                 style="cursor: pointer"--}}
                            {{--                                 viewBox="0 0 448 512"--}}
                            {{--                                 onclick="copyPassword()"--}}
                            {{--                            >--}}
                            {{--                                <path--}}
                            {{--                                    d="M208 0H332.1c12.7 0 24.9 5.1 33.9 14.1l67.9 67.9c9 9 14.1 21.2 14.1 33.9V336c0 26.5-21.5 48-48 48H208c-26.5 0-48-21.5-48-48V48c0-26.5 21.5-48 48-48zM48 128h80v64H64V448H256V416h64v48c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V176c0-26.5 21.5-48 48-48z"/>--}}
                            {{--                            </svg>--}}
                            <input type="name" class="form-control" id="password" placeholder="password"
                                   name="password">
                        </div>
                        <div class="form-group">
                            <label>Роль</label>
                            <select class="form-control"
                                    id="role"
                                    name="role"
                                    onchange="checkRole()">
                                @foreach($roles as $role)
                                    <option
                                        value="{{ $role->id }}" {{ $user->roles->pluck('id')->contains($role->id) ? 'selected' : '' }}>
                                        {{ $role->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                        <div class="form-group">
                            <label>Дэск</label>
                            <input type="hidden" name="desk_id" value="{{ $user->desk_id }}">
                            <select id="deskFilter" name="desk_id"
                                    class="form-control custom-select filter-select">
                                {{--                                {{ !$user->hasRole('super-admin') ? 'disabled' : '' }}--}}
                                <option value="" {{ empty($user->desk_id) ? 'selected' : '' }}>нет</option>
                                @foreach($desks as $desk)
                                    <option
                                        value="{{ $desk->desk_id }}"
                                        {{ ($user->desk_id == $desk->desk_id) ? 'selected' : '' }}>
                                        {{ $desk->desk }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Команда</label>
                            <input type="hidden" name="team_id" value="{{ $user->team_id }}">
                            <select id="teamFilter" name="team_id" class="form-control custom-select filter-select">
                                <option value="" {{ empty($user->team_id) ? 'selected' : '' }}>нет</option>
                                @foreach($teams as $team)
                                    <option value="{{ $team->team_id }}"
                                        {{--                                        {{ ($user->team_id == $team->team_id && ($user->hasRole('teamlead') || $user->hasRole('retention_teamlead'))) ? 'selected' : '' }}>--}}
                                        {{ ($user->team_id == $team->team_id) ? 'selected' : '' }}>
                                        {{ $team->team }}
                                    </option>
                                @endforeach
                            </select>
                        </div>


                    </div>
                    @can('set permissions')
                        <div class="form-group ml-4">
                            <label>Права пользователя</label>
                            <div class="row mt-3 mb-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="selectAll">
                                    <label for="selectAll" class="custom-control-label">Выбрать все</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createLead"
                                           name="permissions[]"
                                           value="create lead" {{ $user->permissions->contains('name', 'create lead') ? 'checked' : '' }}>
                                    <label for="createLead" class="custom-control-label">Создание лида</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteLead"
                                           name="permissions[]"
                                           value="delete lead" {{ $user->permissions->contains('name', 'delete lead') ? 'checked' : '' }}>
                                    <label for="deleteLead" class="custom-control-label">Удаление лида</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="assignLead"
                                           name="permissions[]"
                                           value="assign lead" {{ $user->permissions->contains('name', 'assign lead') ? 'checked' : '' }}>
                                    <label for="assignLead" class="custom-control-label">Выдача лида</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="importLeads"
                                           name="permissions[]"
                                           value="import leads" {{ $user->permissions->contains('name', 'import leads') ? 'checked' : '' }}>
                                    <label for="importLeads" class="custom-control-label">Импорт лида</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllLeads"
                                           name="permissions[]"
                                           value="view all leads" {{ $user->permissions->contains('name', 'view all leads') ? 'checked' : '' }}>
                                    <label for="viewAllLeads" class="custom-control-label">Видеть все лиды</label>
                                </div>

                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteСomments"
                                           name="permissions[]"
                                           value="delete comments" {{ $user->permissions->contains('name', 'delete comments') ? 'checked' : '' }}>
                                    <label for="deleteСomments" class="custom-control-label">Удалять коментарии</label>
                                </div>
                            </div>

                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createUser"
                                           name="permissions[]"
                                           value="create user" {{ $user->permissions->contains('name', 'create user') ? 'checked' : '' }}>
                                    <label for="createUser" class="custom-control-label">Создание пользователей</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteUser"
                                           name="permissions[]"
                                           value="delete user" {{ $user->permissions->contains('name', 'delete user') ? 'checked' : '' }}>
                                    <label for="deleteUser" class="custom-control-label">Удаление пользователей</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllUsers"
                                           name="permissions[]"
                                           value="view all users" {{ $user->permissions->contains('name', 'view all users') ? 'checked' : '' }}>
                                    <label for="viewAllUsers" class="custom-control-label">Видеть всех
                                        пользователей</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="editingUser"
                                           name="permissions[]"
                                           value="editing user" {{ $user->permissions->contains('name', 'editing user') ? 'checked' : '' }}>
                                    <label for="editingUser" class="custom-control-label">Редактировать
                                        пользователей</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="setPermissions"
                                           name="permissions[]"
                                           value="set permissions" {{ $user->permissions->contains('name', 'set permissions') ? 'checked' : '' }}>
                                    <label for="setPermissions" class="custom-control-label">Устанавливать
                                        разрешения</label>
                                </div>
                            </div>

                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createDesk"
                                           name="permissions[]"
                                           value="create desk" {{ $user->permissions->contains('name', 'create desk') ? 'checked' : '' }}>
                                    <label for="createDesk" class="custom-control-label">Создание деска</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteDesk"
                                           name="permissions[]"
                                           value="delete desk" {{ $user->permissions->contains('name', 'delete desk') ? 'checked' : '' }}>
                                    <label for="deleteDesk" class="custom-control-label">Удаление деска</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllDesks"
                                           name="permissions[]"
                                           value="view all desks" {{ $user->permissions->contains('name', 'view all desks') ? 'checked' : '' }}>
                                    <label for="viewAllDesks" class="custom-control-label">Видеть все дески</label>
                                </div>
                            </div>


                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createCountry"
                                           name="permissions[]"
                                           value="create country" {{ $user->permissions->contains('name', 'create country') ? 'checked' : '' }}>
                                    <label for="createCountry" class="custom-control-label">Создание страны</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteCountry"
                                           name="permissions[]"
                                           value="delete country" {{ $user->permissions->contains('name', 'delete country') ? 'checked' : '' }}>
                                    <label for="deleteCountry" class="custom-control-label">Удаление страны</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllCountries"
                                           name="permissions[]"
                                           value="view all countries" {{ $user->permissions->contains('name', 'view all countries') ? 'checked' : '' }}>
                                    <label for="viewAllCountries" class="custom-control-label">Видеть все страны</label>
                                </div>
                            </div>


                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createStatus"
                                           name="permissions[]"
                                           value="create status" {{ $user->permissions->contains('name', 'create status') ? 'checked' : '' }}>
                                    <label for="createStatus" class="custom-control-label">Создание статуса</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteStatus"
                                           name="permissions[]"
                                           value="delete status" {{ $user->permissions->contains('name', 'delete status') ? 'checked' : '' }}>
                                    <label for="deleteStatus" class="custom-control-label">Удаление статуса</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="changeColor"
                                           name="permissions[]"
                                           value="change color" {{ $user->permissions->contains('name', 'change color') ? 'checked' : '' }}>
                                    <label for="changeColor" class="custom-control-label">Изменять цвет статуса</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllStatuses"
                                           name="permissions[]"
                                           value="view all statuses" {{ $user->permissions->contains('name', 'view all statuses') ? 'checked' : '' }}>
                                    <label for="viewAllStatuses" class="custom-control-label">Видеть все статусы</label>
                                </div>
                            </div>

                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="createTeam"
                                           name="permissions[]"
                                           value="create team" {{ $user->permissions->contains('name', 'create team') ? 'checked' : '' }}>
                                    <label for="createTeam" class="custom-control-label">Создание команды</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="deleteTeam"
                                           name="permissions[]"
                                           value="delete team" {{ $user->permissions->contains('name', 'delete team') ? 'checked' : '' }}>
                                    <label for="deleteTeam" class="custom-control-label">Удаление команды</label>
                                </div>
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="viewAllTeams"
                                           name="permissions[]"
                                           value="view all teams" {{ $user->permissions->contains('name', 'view all teams') ? 'checked' : '' }}>
                                    <label for="viewAllTeams" class="custom-control-label">Видеть все команды</label>
                                </div>
                            </div>
                            <hr>
                            <div class="row mt-3">
                                <div class="custom-control custom-checkbox col-md-2">
                                    <input class="custom-control-input" type="checkbox" id="seeUsersComments"
                                           name="permissions[]"
                                           value="see users comments" {{ $user->permissions->contains('name', 'see users comments') ? 'checked' : '' }}>
                                    <label for="seeUsersComments" class="custom-control-label">Видеть комментарии лида</label>
                                </div>
                            </div>
                            <hr>

                        </div>
                    @endcan
                    <div class="card-footer">
                        <button type="submit" class="btn btn-primary">Обновить</button>
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
            window.onload = checkRole;

            function copyPassword() {
                var passwordInput = document.getElementById("password");
                var password = passwordInput.value; // Get the value of the password input
                if (password) {
                    // Only execute the copy operation if the password is not empty
                    var tempTextarea = document.createElement("textarea");
                    tempTextarea.value = password;

                    document.body.appendChild(tempTextarea);
                    tempTextarea.select();
                    document.execCommand("copy");

                    document.body.removeChild(tempTextarea);
                }
            }

            // function copyPassword() {
            //     var passwordInput = document.getElementById("password");
            //     passwordInput.select();
            //     document.execCommand("copy");
            // }
        </script>
    @endpush
@endsection

