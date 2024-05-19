@extends('layouts.main-layout')

@section('content')
    @push('styles')
        <style>
            .card {
                height: 100%;
                display: flex;
                flex-direction: column;
            }

            .card-body {
                flex-grow: 1;
            }

            .margin-bottom {
                margin-bottom: 20px;
            }
        </style>
    @endpush
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>История изменений лида</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">История изменений лида</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">История изменений {{$lead->name}}</h3>
                </div>

                <div class="card-body">

                    <div class="row">
                        <div class="col-12">
                            <div class="card">
                                <div class="card-header">
                                    <h3 class="card-title">История изменений</h3>
                                </div>
                                <div class="card-body table-responsive p-0">
                                    <table class="table table-hover text-nowrap">
                                        <thead>
                                        <tr>
                                            <th>Дата</th>
                                            <th>Тип изменения</th>
                                            <th>Детали</th>
                                            <th>Изменено</th>
                                        </tr>
                                        </thead>
                                        <tbody>
                                        @foreach($changes as $change)
                                            <tr>
                                                <td>{{$change->change_date}}</td>
                                                <td>
                                                    @switch($change->change_type)
                                                        @case('payment_added')
                                                            Внесен платёж
                                                            @break
                                                        @case('user_id_changed')
                                                            Лид передан
                                                            @break
                                                        @case('comment_added')
                                                            Комментарий добавлен
                                                            @break
                                                        @case('assigned_to_user')
                                                            Лид закреплен за
                                                            @break
                                                        @case('created')
                                                            Лид создан
                                                            @break
                                                        @case('status_changed')
                                                            Статус изменён
                                                            @break
                                                        @default
                                                            {{$change->change_type}}
                                                    @endswitch
                                                </td>
                                                <td>
                                                    @if($change->change_type == 'user_id_changed')
                                                        @if($change->oldUser && $change->newUser)
                                                            От {{ $change->oldUser->name }}
                                                            до {{ $change->newUser->name }}
                                                        @elseif($change->newUser)
                                                            До {{ $change->newUser->name }}
                                                        @endif
                                                    @elseif($change->change_type == 'status_changed')
                                                        @if($change->oldStatus && $change->newStatus)
                                                            С "{{ $change->oldStatus->name }}" на
                                                            "{{ $change->newStatus->name }}"
                                                        @elseif($change->newStatus)
                                                            На "{{ $change->newStatus->name }}"
                                                        @endif
                                                    @elseif($change->change_type == 'payment_added')
                                                        Внесено {{$change->old_value}}$
                                                    @elseif($change->change_type == 'assigned_to_user')
                                                        {{$change->new_value}}
                                                    @else
                                                        -
                                                    @endif
                                                </td>
                                                <td>{{$change->user->name}}</td>
                                            </tr>
                                        @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>
                        </div>
                    </div>


                </div>
                <div class="mt-3 mb-3 text-left">
                    <a href="{{ route('showLeadPage',['id'=>$lead->id])}}" class="btn btn-primary"
                       style="width: 120px; margin-left: 20px;">Назад</a>
                </div>
        </section>
    </div>
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
        </div>
    </footer>

@endsection
