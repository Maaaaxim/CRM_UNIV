@extends('layouts.main-layout')

@section('content')
    <div class="content-wrapper">
        <!-- Content Header (Page header) -->
        <section class="content-header">
            <div class="container-fluid">
                <div class="row mb-2">
                    <div class="col-sm-6">
                        <h1>Створення ліда</h1>
                    </div>
                    <div class="col-sm-6">
                        <ol class="breadcrumb float-sm-right">
                            <li class="breadcrumb-item"><a href="#">Головна</a></li>
                            <li class="breadcrumb-item active">створення лідів</li>
                        </ol>
                    </div>
                </div>
            </div><!-- /.container-fluid -->
        </section>
        <section class="content">
            <div class="card card-primary">
                <div class="card-header">
                    <h3 class="card-title">Створити лід</h3>
                </div>

                <form method="post" action="{{route('createLead')}}">
                    @csrf
                    <div class="card-body">
                        <div class="form-group">
                            <label for="name">Ім'я</label>
                            <input type="name" class="form-control" id="name" placeholder="Ім'я"
                                   name="name" required>
                        </div>
                        <div class="form-group">
                            <label for="email">Пошта</label>
                            <input type="email" class="form-control" id="email" placeholder="Пошта"
                                   name="email" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Телефон</label>
                            <input type="tel" class="form-control" id="phone" placeholder="+380-1488-228"
                                   name="phone" required>
                        </div>
                        <div class="form-group">
                            <label for="phone">Кому додати</label>
                            <select id="salesFilter" name="sales_id"
                                    class="form-control custom-select filter-select">
                                <option value="free">Вільний</option>
                                @foreach($users as $user)
                                    <option value="{{ $user->id }}">
                                        {{ $user->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone">Статус</label>
                            <select id="salesFilter" name="status_id"
                                    class="form-control custom-select filter-select">
                                @foreach($statuses as $status)
                                    <option value="{{ $status->id }}">
                                        {{ $status->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone">Країна</label>
                            <select id="countriesFilter" name="country_id"
                                    class="form-control custom-select filter-select">
                                @foreach($countries as $country)
                                    <option value="{{ $country->country_id }}">
                                        {{ $country->country }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="phone">Афіл</label>
                            <input type="text" class="form-control" name="Affiliate">
                        </div>
                        <div class="form-group">
                            <label for="phone">Реклама</label>
                            <input type="tel" class="form-control" name="Advert">
                        </div>
                        <div class="form-group">
                            <label for="phone">Цінність</label>
                            <input type="tel" class="form-control" name="lead_value">
                        </div>
                    </div>

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
@endsection
