<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Document</title>
    <link rel="stylesheet" href="{{asset('assets/css/admin.css')}}">
    <style>
        .logo-container {
            display: flex;
            flex-direction: column; /* Это делает содержимое контейнера вертикальным */
            align-items: center; /* Это центрирует содержимое по горизонтали */
            text-align: center; /* Это гарантирует, что текст также будет центрирован */
        }

        .logo-image {
            width: 100px; /* Вы можете установить нужную вам ширину */
            height: auto; /* Это сохранит пропорции изображения */
            margin-bottom: 10px; /* Отступ снизу для отделения логотипа от текста */
        }

        .dengi {
            width: 175px;
            height: 175px;
        }

    </style>
</head>
<body class="login-page" style="min-height: 495.6px;">
<div class="login-box">
    <div class="login-logo">
        <a href="#" class="logo-container">
            <div class="logo-text">
                <b>CRM</b>
            </div>
        </a>
    </div>

    <div class="card">
        <div class="card-body login-card-body">
            <p class="login-box-msg">Ввійдіть в систему
                {{auth()->check()}}
            </p>

            <audio id="audio"></audio>

            <form action="{{route('signin')}}" method="post">

                @csrf

                <div class="input-group mb-3">
                    <input type="name" class="form-control user" placeholder="Имя" name="name">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-envelope"></span>
                        </div>
                    </div>
                </div>
                <div class="input-group mb-3">
                    <input type="password" class="form-control" placeholder="Пароль" name="password">
                    <div class="input-group-append">
                        <div class="input-group-text">
                            <span class="fas fa-lock"></span>
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-1 text-center">
                        <button type="submit" class="btn btn-primary">Ввійти</button>
                    </div>
                </div>
            </form>
        </div>


    </div>

</div>


<script src="{{asset('assets/js/admin.js')}}"></script>

<script>

</script>


</body>
</html>
