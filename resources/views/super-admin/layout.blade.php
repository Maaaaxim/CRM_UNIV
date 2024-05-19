@extends('layouts.main-layout')

@section('layout')

    <!-- Site wrapper -->
    <div class="wrapper">
        <!-- Navbar -->
        <nav class="main-header navbar navbar-expand navbar-white navbar-light">
            <!-- Left navbar links -->
            <ul class="navbar-nav">
                <li class="nav-item">
                    <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="../../index3.html" class="nav-link">Home</a>
                </li>
                <li class="nav-item d-none d-sm-inline-block">
                    <a href="#" class="nav-link">Contact</a>
                </li>
            </ul>

            <!-- Right navbar links -->
            <ul class="navbar-nav ml-auto">
                <!-- Navbar Search -->

                <!-- Messages Dropdown Menu -->

                <li class="nav-item">
                    <a class="nav-link" data-widget="fullscreen" href="#" role="button">
                        <i class="fas fa-expand-arrows-alt"></i>
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-widget="control-sidebar" data-slide="true" href="#" role="button">
                        <i class="fas fa-th-large"></i>
                    </a>
                </li>
            </ul>
        </nav>
        <!-- /.navbar -->

        <!-- Main Sidebar Container -->
        <aside class="main-sidebar sidebar-dark-primary elevation-4">
            <!-- Brand Logo -->
            <a href="../../index3.html" class="brand-link">
                <img src="{{asset('public/assets/img/logo.jpg')}}" alt="AdminLTE Logo"
                     class="brand-image img-circle elevation-3"
                     style="opacity: .8">
                <span class="brand-text font-weight-light">CRM1488</span>
            </a>

            <!-- Sidebar -->
            <div class="sidebar">
                <!-- Sidebar user (optional) -->
                <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                    <div class="image">
                        <img src="{{asset('public/assets/img/123123.jpg')}}" class="img-circle elevation-2"
                             alt="User Image">
                    </div>
                    <div class="info">
                        <a href="#" class="d-block">{{auth()->user()->name}}</a>
                        <a href="#" class="nav-link">{{auth()->user()->role}}</a>
                        <a href="{{route('logout')}}" class="nav-link">Выйти</a>
                    </div>
                </div>


                <!-- Sidebar Menu -->
                <nav class="mt-2">
                    <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">


                        <li class="nav-item">
                            <a href="{{route('index')}}" class="nav-link">
                                <i class="nav-icon far fa-image"></i>
                                <p>
                                    Создать пользователя
                                </p>
                            </a>
                        </li>
                        @hasrole('super-admin')
                        <li class="nav-item">
                            <a href="{{route('showUsers')}}" class="nav-link">
                                <i class="nav-icon far fa-image"></i>
                                <p>
                                    Пользователиxx
                                    Роль: {{auth()->user()->getRoleNames()}}

                                </p>
                            </a>
                        </li>
                        @endhasrole

                        <li class="nav-item">
                            <a href="{{asset('showLeads')}}" class="nav-link">
                                <i class="nav-icon far fa-image"></i>
                                <p>
                                    Все лиды
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{asset('showLeads')}}" class="nav-link">
                                <i class="nav-icon far fa-image"></i>
                                <p>
                                    Мои лиды
                                </p>
                            </a>
                        </li>
                        <li class="nav-item">
                            <a href="{{asset('showLeads')}}" class="nav-link">
                                <i class="nav-icon far fa-image"></i>
                                <p>
                                   Создать лид
                                </p>
                            </a>
                        </li>
                    </ul>
                </nav>
                <!-- /.sidebar-menu -->
            </div>
            <!-- /.sidebar -->
        </aside>

        <!-- Main content -->
        @yield('content')
        <!-- /.card -->

        <!-- Control Sidebar -->
        <aside class="control-sidebar control-sidebar-dark">
        </aside>
        <!-- /.control-sidebar -->
    </div>
    <!-- ./wrapper -->

@endsection
