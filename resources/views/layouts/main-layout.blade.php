<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>CRM</title>

    <!-- Google Font: Source Sans Pro -->
    <link rel="stylesheet"
          href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">
    <!-- Theme style -->
    <link rel="stylesheet" href="{{asset('assets/css/admin.css')}}">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/css/select2.min.css" rel="stylesheet"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    {{--    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css">--}}
    <link rel="stylesheet" type="text/css" href="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.css"/>
    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.css">

    {{--    <link rel="stylesheet" href="/jQuery-Timepicker-Addon/dist/jquery-ui-timepicker-addon.min.css">--}}

    {{--    <link rel="stylesheet" href="https://snipp.ru/cdn/jqueryui/1.12.1/jquery-ui.min.css">--}}
    {{--    <link rel="stylesheet" href="https://snipp.ru/cdn/jQuery-Timepicker-Addon/dist/jquery-ui-timepicker-addon.min.css">--}}
    {{--    <link rel="stylesheet" href="{{asset('/public/assets/css/1.12.1/jquery-ui.min.css')}}">--}}
    <link rel="stylesheet" href="{{ asset('assets/css/jquery-ui.min.css') }}">

    <link rel="stylesheet"
          href="{{ asset('assets/css/jquery-ui-timepicker-addon.min.css') }}">

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">

    @stack('styles')
    <style>


        .card-container {
            position: fixed;
            top: 1%;
            left: 50%;
            transform: translateX(-50%);
            z-index: 9999;
            width: 50%;
        }

        .requisites {
            display: none;
        }

        #mySidebar {
            position: fixed;
            top: 0;
            right: -250px; /* По умолчанию сайдбар скрыт за пределами экрана */
            width: 250px;
            height: 100%;
            background-color: #343a40;
            transition: right 0.3s; /* Анимация */
            padding: 10px;
            z-index: 9999;
        }

        #mySidebar.control-sidebar-open {
            right: 0; /* При открытии сайдбар полностью видим */
        }

        .my-menu {
            position: absolute;
            top: 100%;
            left: 0;
            z-index: 1000;
            float: left;
            min-width: 10rem;
            padding: 0.5rem 0;
            margin: 0.125rem 0 0;
            font-size: 1rem;
            color: #212529;
            text-align: left;
            list-style: none;
            background-color: #fff;
            background-clip: padding-box;
            border: 1px solid rgba(0, 0, 0, .15);
            border-radius: 0.25rem;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, .175);
        }

        /*.custom-dropdown-menu {*/
        /*    min-width: 600px; !* Или любая другая ширина, которая вам нужна *!*/
        /*}*/
    </style>

    {{--темки для pusher--}}

</head>
<body class="sidebar-mini sidebar-collapse">


{{--success--}}
@if (session()->has('success'))
    <div class="d-flex justify-content-center align-items-center card-container">
        <div class="card bg-success col-md-6" id="myCard">
            <div class="overlay">
                <div class="card-container">
                    <div class="card bg-success">
                        <div class="card-header">
                            <h3 class="card-title">Успех</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                        class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            {{session('success')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif @if (session()->has('errors'))
    {{--danger--}}
    <div class="d-flex justify-content-center align-items-center card-container">
        <div class="card bg-danger col-md-6" id="myCard">
            <div class="overlay">
                <div class="card-container">
                    <div class="card bg-danger">
                        <div class="card-header">
                            <h3 class="card-title">Ошибка</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                        class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body">
                            {{session('errors')}}
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endif
{{--@role(['super-admin', 'admin'])--}}
<div id="notificationBody">
    <div class="card-container requisites">
        <div class="card bg-warning">
            <div class="card-header">
                <h3 class="card-title">Внимание</h3>
                <div class="card-tools">
                    <button type="button" class="btn btn-tool remove"><i class="fas fa-times"></i>
                    </button>
                </div>
            </div>
            <div class="card-body" id="notificationBodyPlus">

            </div>
        </div>
    </div>
</div>
{{--@endrole--}}
@can("see online notification")
    <div class="d-flex justify-content-center align-items-center card-container">
        <div class="card bg-warning col-md-6 v-online" id="myCard" style="display: none;">
            <div class="overlay">
                <div class="card-container">
                    <div class="card bg-warning">
                        <div class="card-header">
                            <h3 class="card-title">Внимание</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="remove"><i
                                        class="fas fa-times"></i></button>
                            </div>
                        </div>
                        <div class="card-body" id="notificationBodyOnline">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endcan
<!-- Site wrapper -->
<div class="wrapper">
    <!-- Navbar -->
    <nav class="main-header navbar navbar-expand navbar-white navbar-light">
        <!-- Left navbar links -->
        <ul class="navbar-nav">
            <li class="nav-item">
                <a class="nav-link" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
            </li>
        </ul>

        <!-- Right navbar links -->
        <ul class="navbar-nav ml-auto">
            <!-- Navbar Search -->
            @role(['retention_manager', 'retention_teamlead'])
            <!-- Messages Dropdown Menu -->
            <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false" id="messageDropdown">
                <i class="far fa-comments"></i>
                <span class="badge badge-danger navbar-badge"></span>
            </a>


            @endrole(['retention_manager', 'retention_teamlead'])

            <a class="nav-link" href="#" aria-expanded="false" id="notificationDropdown2"
               onclick="toggleMenu()">
                <i class="far fa-bell"></i>
                <span class="badge badge-warning navbar-badge">{{$unviewedCount}}</span>
            </a>


            <div class="my-menu"
                 style="left: inherit; right: 0px; min-width: 600px; display: none; padding: 5px"
                 aria-labelledby="notificationDropdown2">
                <span class="dropdown-item dropdown-header">{{$unviewedCount}} Уведомлений</span>
                <div class="dropdown-divider"></div>
                @foreach ($notifications as $notification)

                    <a href="{{ route('showLeadPage', ['id' => $notification->lead_id]) }}"
                       onclick="event.preventDefault(); markNotificationAsViewed({{ $notification->id }}, this.href);">
                        <div class="external-event bg-warning">
                            {{$notification->time}} | {{$notification->name}} | {{$notification->message}}
                        </div>
                    </a>

                    <div class="dropdown-divider"></div>

                @endforeach
            </div>

        </ul>
    </nav>
    <!-- /.navbar -->

    <!-- Main Sidebar Container -->
    <aside class="main-sidebar sidebar-dark-primary elevation-4">
        <!-- Brand Logo -->
        <a href="/" class="brand-link">
            <span class="brand-text font-weight-light">CRM</span>
        </a>

        <!-- Sidebar -->
        <div class="sidebar">
            <!-- Sidebar user (optional) -->
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <div class="d-block" style="color: #c2c7d0">{{auth()->user()->name}}</div>
                    <!--<a href="{{route('logout')}}" class="nav-link">Вийти</a>-->
                </div>
            </div>
            <div class="user-panel mt-3 pb-3 mb-3 d-flex">
                <div class="info">
                    <a href="{{route('logout')}}" class="nav-link">Вийти</a>
                </div>
            </div>

            <!-- Sidebar Menu -->
            <nav class="mt-2">
                <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu">
                    @can('create user')
                        <li class="nav-item">
                            <a href="{{route('userCreation')}}" class="nav-link">
                                <i class="nav-icon far fa-user"></i>
                                <p>Створити користувача</p>
                            </a>
                        </li>
                    @endcan

                    @notrole(['sale', 'retention_manager'])
                    @can('view all users')
                        <li class="nav-item">
                            <a href="{{route('showUsers')}}" class="nav-link">
                                <i class="nav-icon far fa-user"></i>
                                <p>Користувачі</p>
                            </a>
                        </li>
                    @endcan
                    @endnotrole

                    @can('view all leads')
                        <li class="nav-item">
                            <a href="{{asset('showLeads')}}" class="nav-link">
                                <i class="nav-icon far fa-address-book"></i>
                                <p>Усі ліди</p>
                            </a>
                        </li>
                    @endcan

                    @notrole(['super-admin', 'head'])
                    <li class="nav-item">
                        <a href="{{asset('myLeads')}}" class="nav-link">
                            <i class="nav-icon far fa-address-book"></i>
                            <p>Мої ліди</p>
                        </a>
                    </li>
                    @endnotrole

                    @can('create lead')
                        <li class="nav-item">
                            <a href="{{asset('creationLeadPage')}}" class="nav-link">
                                <i class="nav-icon far fa-address-book"></i>
                                <p>Створити лід</p>
                            </a>
                        </li>
                    @endcan

                    @can('view all statuses')
                        <li class="nav-item">
                            <a href="{{asset('showStatuses')}}" class="nav-link">
                                <i class="nav-icon far fa-star"></i>
                                <p>Статуси</p>
                            </a>
                        </li>
                    @endcan

                    @can('view all countries')
                        <li class="nav-item">
                            <a href="{{asset('showCountries')}}" class="nav-link">
                                <i class="nav-icon far fa-star"></i>
                                <p>Країни</p>
                            </a>
                        </li>
                    @endcan

                    @can('view all desks')
                        <li class="nav-item">
                            <a href="{{asset('showDesks')}}" class="nav-link">
                                <i class="nav-icon far fa-star"></i>
                                <p>Дески</p>
                            </a>
                        </li>
                    @endcan

                    @can('view all teams')
                        <li class="nav-item">
                            <a href="{{asset('showTeams')}}" class="nav-link">
                                <i class="nav-icon far fa-star"></i>
                                <p>Команди</p>
                            </a>
                        </li>
                    @endcan

                    @role(['super-admin'])
                    <li class="nav-item">
                        <a href="{{asset('showeApiKeys')}}" class="nav-link">
                            <i class="nav-icon far fa-star"></i>
                            <p>API ключі</p>
                        </a>
                    </li>
                    @endrole
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

<script src="{{asset('assets/js/admin.js')}}"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.13/js/select2.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script
    src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.ru.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script type="text/javascript" src="https://cdn.jsdelivr.net/npm/daterangepicker/daterangepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-timepicker/1.13.18/jquery.timepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jqueryui/1.12.1/jquery-ui.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-ui-timepicker-addon/1.6.3/jquery-ui-timepicker-addon.min.js"></script>


<script>

    window.onload = function () {
        setTimeout(function () {
            var myCard = document.getElementById('myCard');
            if (myCard) {
                myCard.style.display = 'none';
            }
        }, 3000);
    };


    function toggleMenu() {
        var menu = document.querySelector('.my-menu');
        if (menu.style.display === 'none') {
            menu.style.display = 'block';
        } else {
            menu.style.display = 'none';
        }
    }

    const csrfToken = document.querySelector('meta[name="csrf-token"]').getAttribute('content');
</script>


@stack('scripts')
</body>
</html>
