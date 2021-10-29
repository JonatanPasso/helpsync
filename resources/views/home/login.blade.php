<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="Service Desk - Verttice - Login">
    <meta name="author" content="Verttice">

    <meta name="google-site-verification" content="melOtp3cEXEZQ2_2LnwgeoB93Cd64D_tm6E0m1Qruuk"/>

    {{--    flav icon--}}
    @include('layout.flavicon')
    {{--end flav icon--}}


    <title>Service Desk Verttice - Login</title>

    <link rel="shortcut icon" href="/webapp/src/assets/favicon.png">
    <link
            href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"
            rel="stylesheet">


    <link href=/webapp/dist/css/app.css rel=preload as=style>
    <link href=/webapp/dist/css/chunk-vendors.css rel=preload as=style>
    <link href=/webapp/dist/js/app.js rel=preload as=script>
    <link href=/webapp/dist/js/chunk-vendors.js rel=preload as=script>
    <link href=/webapp/dist/css/chunk-vendors.css rel=stylesheet>
    <link href=/webapp/dist/css/app.css rel=stylesheet>

    <style>


        html {
            background-image: url(/webapp/src/assets/login/bg-login.jpg);
            background-size: 100% 100%;
            background-repeat: no-repeat;
        }


        @media (min-width: 768px) {
            html {
                background-image: url(/webapp/src/assets/login/bg-login.jpg);
            }
        }


        .bg-gradient-primary {
            background-color: transparent;
            background-image: none;
        }

        .bg-login-image {
            background: url("/webapp/src/assets/login/verttice-oficial.svg");
            background-repeat: no-repeat;
            background-size: 89%;
            background-position: center center;
            background-color: #f8f9fc;
            border-right: 3px solid #496edb;
        }

    </style>

</head>

<body class="bg-gradient-primary with-bv">

{{--<div class="bg-video">--}}
{{--    <video autoplay loop muted id="aaa">--}}
{{--        <source--}}
{{--            src="/cdn/videos/{{ \Illuminate\Support\Arr::random(['Computer2.mp4','Computer.mp4','Lights2.mp4','VUE_20200312185442.mp4','VUE_20200312185915.mp4','Computer3.mp4','iluminação.mp4','Lights.mp4','VUE_20200312185804.mp4','VUE_20200312195323.mp4'])  }}"--}}
{{--            type="video/mp4">--}}
{{--    </video>--}}
{{--</div>--}}

</video>


<div class="container">

    <!-- Outer Row -->
    <div class="row justify-content-center">

        <div class="col-xl-10 col-lg-12 col-md-9">

            <div class="card o-hidden border-0 shadow-lg my-5">
                <div class="card-body p-0">
                    <!-- Nested Row within Card Body -->
                    <div class="row">
                        <div class="col-lg-6 d-none d-lg-block bg-login-image"></div>
                        <div class="col-lg-6">
                            <div class="p-5">
                                <div class="text-center">
                                    <h1 class="h4 text-gray-900 mb-4">Entrar</h1>
                                </div>
                                <form class="user" action="{{url('login')}}" method="post">
                                    <div class="form-group">
                                        <input type="text" class="form-control form-control-user"
                                               id="login" name="login" aria-describedby="emailHelp"
                                               placeholder="EMAIL, TELEFONE OU CPF">
                                    </div>
                                    <div class="form-group">
                                        <input type="password" class="form-control form-control-user"
                                               id="senha" name="senha" placeholder="MINHA SENHA">
                                    </div>
                                    <div class="form-group">
                                        <div class="custom-control custom-checkbox small">
                                            <input type="checkbox" class="custom-control-input" id="salvar-sessao"
                                                   name="salvar-sessao">
                                            <label class="custom-control-label" for="salvar-sessao">SALVAR
                                                SESSÃO</label>
                                        </div>
                                    </div>

                                    @if(isset($erro))
                                        <div class="alert alert-danger" role="alert">
                                            <i class="fa fa-exclamation-triangle"></i> {{$erro}}.
                                        </div>
                                    @endif

                                    <button type="submit" class="btn btn-primary btn-user btn-block">
                                        ENTRAR
                                    </button>
{{--                                    <hr>--}}
                                </form>
                                <br>
                                <br>
{{--                                <hr>--}}
{{--                                <div class="text-center">--}}
{{--                                    <a class="small" href="forgot-password.html">Recuper meu Acesso?</a>--}}
{{--                                </div>                               --}}
                            </div>
                        </div>
                    </div>
                </div>
            </div>

        </div>

    </div>

</div>

<!-- Bootstrap core JavaScript-->
<script src="vendor/jquery/jquery.min.js"></script>
<script src="vendor/bootstrap/js/bootstrap.bundle.min.js"></script>

<!-- Core plugin JavaScript-->
<script src="vendor/jquery-easing/jquery.easing.min.js"></script>

<!-- Custom scripts for all pages-->
<script src="js/sb-admin-2.min.js"></script>

<script>


    //    var videoBG = null;
    //    document.addEventListener("DOMContentLoaded", function (event) {
    //
    //        videoBG = document.getElementById('teste');
    //
    //        videoBG.src = '/cdn/videos/Computer2.mp4';
    //
    //        videoBG.play();
    //
    //    });


</script>

<style>

    html, body {
        height: 100%;
    }

    .with-bv {

        position: relative;

    }

    .container {
        position: inherit;
        z-index: 2;
    }

    .bg-video {
        position: absolute;
        z-index: 1;
        width: 100%;
        height: 100%;
        overflow: hidden;
    }

    .with-bv .card, .with-bv .bg-login-image {
        background-color: #ffffffab;
    }

    .video-land {
        width: 100%;
        height: auto;
    }

    .video-port {
        width: auto;
        height: 100%;
    }


</style>

<script>

    // var vb = document.getElementById('aaa');
    //
    // setInterval(function () {
    //
    //     if (window.screen.width >= window.screen.height) {
    //
    //         //landscpae
    //         console.log('landscpaae');
    //         vb.className = 'video-land';video-land
    //
    //     } else {
    //
    //         //portrait
    //         console.log('portratig');
    //         vb.className = 'video-port';
    //     }
    //
    // }, 100);

</script>


</body>


</html>
