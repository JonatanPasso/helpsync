<!DOCTYPE html>
<html lang="en">
<head>

    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="">
    <meta name="author" content="">

{{--    <style>--}}
{{--        html, body, #app {--}}
{{--            height: 100%;--}}
{{--            background-color: #000000;--}}
{{--        }--}}

{{--        @keyframes zoom {--}}
{{--            from {--}}
{{--                transform: scale(0);--}}
{{--            }--}}

{{--            to {--}}
{{--                transform: scale(1);--}}
{{--            }--}}
{{--        }--}}

{{--        .load-img-boot {--}}
{{--            height: 100%;--}}
{{--            background-image: url(data:image/png;base64,{{@base64_encode(file_get_contents(app()->basePath("public/webapp/src/assets/logo-wide.png")))}});--}}
{{--            background-position: center center;--}}
{{--            background-repeat: no-repeat;--}}
{{--            background-size: 90% auto;--}}

{{--            animation-duration: 8s;--}}
{{--            animation-name: zoom;--}}
{{--            animation-iteration-count: infinite;--}}
{{--            animation-direction: alternate;--}}
{{--        }--}}


{{--    </style>--}}


{{--    <link--}}
{{--        href="https://fonts.googleapis.com/css?family=Nunito:200,200i,300,300i,400,400i,600,600i,700,700i,800,800i,900,900i"--}}
{{--        rel="stylesheet">--}}

    <title>Service Desk Verttice</title>

    <script>var GOOGLE_MAPS_KEY = 'AIzaSyDPFPYYYZhqos-nWHyOC-IGd5wgAjJ6OpM';
        var HOST_API = '{{url('')}}';
        var TOKEN = '{{@$_COOKIE['authorization']}}';</script>

{{--    <link href=/webapp/dist/css/app.css rel=preload as=style>--}}
{{--    <link href=/webapp/dist/css/chunk-vendors.css rel=preload as=style>--}}
{{--    <link href=/webapp/dist/js/app.js rel=preload as=script>--}}
{{--    <link href=/webapp/dist/js/chunk-vendors.js rel=preload as=script>--}}
    <link href=/webapp/dist/css/chunk-vendors.css?t={{time()}} rel=stylesheet>
    <link href=/webapp/dist/css/app.css?t={{time()}} rel=stylesheet>

    <script>
        var INFOSESSION = @json($infoSession);
    </script>

</head>

<body>

<div id="app">
    <div class="load-img-boot"></div>

</div>
<!-- built files will be auto injected -->
<script src=/webapp/dist/js/chunk-vendors.js?t={{time()}}></script>
<script src=/webapp/dist/js/app.js?t={{time()}}></script>

</body>
</html>
