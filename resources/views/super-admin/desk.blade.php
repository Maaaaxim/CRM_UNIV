@extends('layouts.main-layout')

@section('content')

    <div class="content-wrapper">
        <!-- Создание дэска -->
        @can('delete desk')
            <form method="post" action="{{route('createDesk')}}">
                @csrf
                <div class="card-body">
                    <div class="form-group">
                        <label for="desk">Дэск</label>
                        <input type="text" class="form-control" id="desk" placeholder="Дэск" name="desk" required>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">Создать</button>
                </div>
            </form>
        @endcan
        <!-- Список дэсок -->
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Дэски</h3>
                </div>

                <div class="card-body">
                    <table class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>ID</th>
                            <th>Дэск</th>
                            @can('delete desk')
                                <th>Удалить</th>
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
                                        <button type="button" class="btn btn-danger">Удалить дэск</button>
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
