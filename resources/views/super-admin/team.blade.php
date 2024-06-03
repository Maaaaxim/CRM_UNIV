@extends('layouts.main-layout')

@section('content')

    <div class="content-wrapper">
        <!-- Створення команди -->
        @can('delete team')
            <form method="post" action="{{route('createTeam')}}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="team">Команда</label>
                        <input type="text" class="form-control" id="team" placeholder="Команда" name="team" required>
                    </div>
                    <div class="form-group">
                        <label>Деск</label>
                        <input type="hidden" value="{{ Auth::user()->desk_id }}">
                        <select name="desk_id" id="deskFilter"
                                class="form-control custom-select filter-select" {{ !Auth::user()->hasRole('super-admin') ? 'disabled' : '' }}>
                            @foreach($desks as $desk)
                                <option
                                    value="{{ $desk->desk_id }}" {{ (Auth::user()->desk_id == $desk->desk_id) ? 'selected' : '' }}>
                                    {{ $desk->desk }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Створити</button>
                </div>
            </form>
        @endcan
        <!-- Список команд -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Команди</h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Команда</th>
                            @can('delete team')
                                <th>Видалити</th>
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($teams as $team)
                            <tr>
                                <td>{{ $team->team_id }}</td>
                                <td>{{ $team->team }}</td>
                                @can('delete team')
                                    <td>
                                        <a href="{{route('deleteTeam', ['id' => $team->team_id])}}">
                                            <button type="button" class="btn btn-danger">Видалити команду</button>
                                        </a>
                                    </td>
                                @endcan
                            </tr>
                        @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection
