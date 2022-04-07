<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500&display=swap" rel="stylesheet">

</head>

<style type="text/css">
    html,body{
        width: 100%;
        height: 100%;
    }
    body{
        font-family: 'Roboto', sans-serif;
        background: #CFDE40 url('{{asset('portal/assets/images/background.jpg')}}') no-repeat;
        background-size: cover;
        background-position: center;
        text-align: center;
        margin: 0;
    }
    .login{
        position: relative;
        top: 50%;
        transform: translateY(-50%);
    }
    .login .brand{
        margin-bottom: 30px;
    }
    .login .brand img{
        width: 150px;
    }
    .login .links .btn-login{
        color: #fff;
        font-size: 25px;
        font-weight: 400;
        display: table;
        margin: 0 auto 15px auto;
        text-decoration: none;
        padding: 0 15px;
    }
    .login .links .btn-login:hover{
        color: #e8ff00;
    }
    .login .links .btn-continue{
        color: #2FAD4D;
        font-size: 18px;
        font-weight: 500;
        display: table;
        margin: auto;
        text-decoration: none;
        background: #fff;
        border-radius: 25px;
        padding: 10px 10px;
        width: 400px;
        text-transform: uppercase;
        letter-spacing: 1px;
    }
    .login .links .btn-continue:hover{
        background: #d0df42;
        color: #fff;
    }
</style>

<body>

<section class="login">

    <div class="brand">

        <img src="{{asset('portal/assets/images/logo.png')}}">

    </div>

    <div class="links">

        <a class="btn-login" href="{{route('login')}}">Login</a>

        <a class="btn-continue" href="{{ Auth::check() ? route('dashboard') : route('landing.page')}}">Continue</a>

    </div>

</section>

</body>
</html>
