@extends('layouts.main-layout')

@section('content')

    <div class="content-wrapper">
        @can('create country')
            <section class="content">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Створити API ключ</h3>
                    </div>

                    <form method="post" action="{{route('createApiKey')}}">
                        @csrf
                        <div class="card-body">
                            <div class="form-group">
                                <label for="name">Назва API ключа</label>
                                <input type="name" class="form-control" id="name" placeholder="Назва"
                                       name="api_name" required>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="submit" class="btn btn-primary">Створити</button>
                        </div>
                    </form>
                </div>
            </section>
        @endcan
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">API ключі</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                                <thead>
                                <tr>
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">ID</th>
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Назва</th>
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">API ключ</th>
                                    @can('delete country')
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Видалити</th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($apis as $api)
                                    <tr class="even">
                                        <td class="sorting_1 dtr-control">{{ $api->id }}</td>
                                        <td class="sorting_1 dtr-control">{{ $api->name }}</td>
                                        <td class="sorting_1 dtr-control">{{ $api->key }}</td>
                                        @can('delete country')
                                            <td>
                                                <a href="{{route('deleteApiKey', ['id' => $api->id])}}">
                                                    <button type="button" class="btn btn-danger">
                                                        Видалити API ключ
                                                    </button>
                                                </a>
                                            </td>
                                        @endcan
                                    </tr>
                                @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-sm-12 col-md-5">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
    <script>
        // Custom scripts can be added here
    </script>
@endsection
