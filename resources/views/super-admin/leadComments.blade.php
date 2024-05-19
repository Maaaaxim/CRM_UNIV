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
                        <h1>Коментарии лида</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Home</a></li>
                            <li class="breadcrumb-item active">Коментарии лида</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>

        <section class="content">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Коментарии {{$lead->name}}</h3>
                </div>

                <div class="card-body">
                    <div class="row">
                        @foreach($comments as $comment)
                        <div class="col-md-3 margin-bottom">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h3 class="card-title">{{$comment->created_at}}</h3>
                                    <div class="card-tools">
                                        <a href="{{ route('deleteComment', ['id' => $comment->id]) }}">
                                            <svg class="delete-icon" style="cursor: pointer"
                                                 xmlns="http://www.w3.org/2000/svg" height="1em"
                                                 viewBox="0 0 448 512">
                                                <path
                                                    d="M135.2 17.7L128 32H32C14.3 32 0 46.3 0 64S14.3 96 32 96H416c17.7 0 32-14.3 32-32s-14.3-32-32-32H320l-7.2-14.3C307.4 6.8 296.3 0 284.2 0H163.8c-12.1 0-23.2 6.8-28.6 17.7zM416 128H32L53.2 467c1.6 25.3 22.6 45 47.9 45H346.9c25.3 0 46.3-19.7 47.9-45L416 128z"/>
                                            </svg>
                                        </a>
                                    </div>

                                </div>

                                <div class="card-body">
                                   {{$comment->body}}
                                </div>

                            </div>

                        </div>
                        @endforeach
                    </div>
                </div>
                <div class="mt-3 mb-3 text-left">
                    <a href="{{ route('showLeadPage',['id'=>$lead->id])}}" class="btn btn-primary" style="width: 120px; margin-left: 20px;">Назад</a>
                </div>
        </section>
    </div>
    <footer class="main-footer">
        <div class="float-right d-none d-sm-block">
        </div>
    </footer>

@endsection
