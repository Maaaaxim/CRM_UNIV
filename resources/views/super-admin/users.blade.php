@extends('layouts.main-layout')


@section('content')

    <div class="content-wrapper">

        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Користувачі</h3>
                </div>

                <div class="card-body">
                    <div id="example2_wrapper" class="dataTables_wrapper dt-bootstrap4">
                        <div class="row">
                            <div class="text-right mb-3 mr-3 col-md-auto">
                                <form method="get" action="{{ url()->current() }}">
                                    <label>Кількість користувачів на сторінці</label>
                                    <select class="form-control leads-from" name="per_page" onchange="this.form.submit()">
                                        <option value="10" @if(request('per_page') == '10') selected @endif>10</option>
                                        <option value="20" @if(request('per_page') == '20') selected @endif>20</option>
                                        <option value="50" @if(request('per_page') == '50') selected @endif>50</option>
                                    </select>
                                </form>
                            </div>

                            @include('parts.FIltersForUsers', ['teams'=>$teams, 'desks'=>$desks])

                        </div>
                        <div class="row">
                            <div class="col-sm-12">
                                <table id="example2" class="table table-bordered table-hover dataTable dtr-inline" aria-describedby="example2_info">
                                    <thead>
                                    <tr>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">ID</th>
                                        <th class="sorting sorting_asc" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-sort="ascending" aria-label="Rendering engine: activate to sort column descending">Ім'я</th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Роль</th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Команда</th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Деск</th>
                                        <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Browser: activate to sort column ascending">Кількість лідів</th>
                                        @can('delete user')
                                            <th class="sorting" tabindex="0" aria-controls="example2" rowspan="1" colspan="1" aria-label="Engine version: activate to sort column ascending">Кнопки</th>
                                        @endcan
                                    </tr>
                                    </thead>
                                    <tbody>
                                    @foreach ($users as $user)
                                        <tr class="even">
                                            <td>{{ $user->id}}</td>
                                            @can('editing user')
                                                <td>
                                                    <a href="{{ route('userPage', ['id' => $user->id]) }}">{{ $user->name }}</a>
                                                </td>
                                            @else
                                                <td>{{ $user->name }}</td>
                                            @endcan
                                            <td>{{ $user->getRoleNames()->implode(', ') }}</td>
                                            <td>{{ $user->team ? $user->team->team : 'Немає команди' }}</td>
                                            <td>{{ $user->desk ? $user->desk->desk : 'Немає деска' }}</td>
                                            <td>{{$user->userLeadsCount ? $user->userLeadsCount : 'Немає лідів'}}</td>
                                            @can('delete user')
                                                <td>
                                                    <a href="{{route('deleteUser', ['id'=>$user->id])}}">
                                                        <button type="button" class="btn btn-danger" onclick="return confirmDeletion('{{ route('deleteUser', ['id' => $user->id]) }}')">Видалити користувача</button>
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
                            <div class="col-sm-12 col-md-7">
                                <div class="dataTables_paginate paging_simple_numbers" id="example2_paginate">
                                    {{ $users->appends(['per_page' => request('per_page')])->links('vendor.pagination.bootstrap-4') }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function confirmDeletion(url) {
                const userConfirmed = confirm('Вы уверены, что хотите удалить этого пользователя?');

                if (userConfirmed) {
                    window.location.href = url;
                }
                return false;
            }

        </script>
    @endpush
@endsection
