@extends('layouts.main-layout')


@section('content')

    <div class="content-wrapper">
        @can('create country')
            <section class="content">
                <div class="card card-primary">
                    <div class="card-header">
                        <h3 class="card-title">Створити країну</h3>
                    </div>

                    <form method="post" action="{{route('createCountry')}}">

                        @csrf

                        <div class="card-body">
                            <div class="form-group">
                                <label for="code">Код країни</label>
                                <input type="text" class="form-control" id="code" placeholder="Код країни"
                                       name="code" required>
                                <label for="name">Країна</label>
                                <input type="text" class="form-control" id="name" placeholder="Країна"
                                       name="country" required>
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
                    <h3 class="card-title">Країни</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        <div class="col-sm-12">
                            <table id="example2" class="table table-bordered table-hover dataTable dtr-inline"
                                   aria-describedby="example2_info">
                                <thead>
                                <tr>
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example2"
                                        rowspan="1" colspan="1" aria-sort="ascending"
                                        aria-label="Rendering engine: activate to sort column descending">ID
                                    </th>
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example2"
                                        rowspan="1" colspan="1" aria-sort="ascending"
                                        aria-label="Rendering engine: activate to sort column descending">Код
                                    </th>
                                    <th class="sorting sorting_asc" tabindex="0" aria-controls="example2"
                                        rowspan="1" colspan="1" aria-sort="ascending"
                                        aria-label="Rendering engine: activate to sort column descending">Країна
                                    </th>
                                    @can('delete country')
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1"
                                            colspan="1" aria-label="Browser: activate to sort column ascending">Видалити
                                        </th>
                                    @endcan
                                </tr>
                                </thead>
                                <tbody>
                                @foreach ($countries as $country)
                                    <tr class="even">
                                        <td class="sorting_1 dtr-control">{{ $country->country_id }}</td>
                                        <td class="sorting_1 dtr-control">{{ $country->code }}</td>
                                        <td class="sorting_1 dtr-control">{{ $country->country }}</td>
                                        @can('delete country')
                                            <td>
                                                <a href="{{route('deleteCountry', ['id' => $country->country_id])}}">
                                                    <button type="button" class="btn btn-danger">
                                                        Видалити країну
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


    @section('scripts')
        <script>

        </script>
    @endsection
@endsection
