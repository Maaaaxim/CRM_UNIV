@extends('layouts.main-layout')

@section('content')

    <div class="content-wrapper">
        <!-- Створення деска -->
        @can('delete desk')
            <form method="post" action="{{route('createDesk')}}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="desk">Деск</label>
                        <input type="text" class="form-control" id="desk" placeholder="Деск" name="desk" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Створити</button>
                </div>
            </form>
        @endcan
        <!-- Список десків -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Дески</h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Деск</th>
                            @can('delete desk')
                                <th>Видалити</th>
                            @endcan
                        </tr>
                        </thead>
                        <tbody>
                        @foreach ($desks as $desk)
                            <tr>
                                <td>{{ $desk->desk_id }}</td>
                                <td>{{ $desk->desk }}</td>
                                @can('delete desk')
                                    <td>
                                        <a href="{{route('deleteDesk', ['id' => $desk->desk_id])}}">
                                            <button type="button" class="btn btn-danger">Видалити деск</button>
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
