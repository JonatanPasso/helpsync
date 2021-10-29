<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>--- TODO ---</title>

    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css">
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css" rel="stylesheet"
          integrity="sha384-9aIt2nRpC12Uk9gS9baDl411NQApFmC26EwAOH8WgZl5MYYxFfc+NcPb1dKGj7Sk" crossorigin="anonymous">

    <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
    <!-- Leave those next 4 lines if you care about users using IE8 -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>


{{--<!-- Navigation -->--}}
{{--<nav class="navbar navbar-expand-lg navbar-dark bg-dark static-top">--}}
{{--    <div class="container">--}}
{{--        <a class="navbar-brand" href="#">AMAZON BRZ</a>--}}
{{--        <button class="navbar-toggler" type="button" data-toggle="collapse" data-target="#navbarResponsive"--}}
{{--                aria-controls="navbarResponsive" aria-expanded="false" aria-label="Toggle navigation">--}}
{{--            <span class="navbar-toggler-icon"></span>--}}
{{--        </button>--}}

{{--        <div class="collapse navbar-collapse" id="navbarResponsive">--}}
{{--            <ul class="navbar-nav ml-auto">--}}
{{--                <li class="nav-item active">--}}
{{--                    <a class="nav-link" href="#">Home--}}
{{--                        <span class="sr-only">(current)</span>--}}
{{--                    </a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">About</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">Services</a>--}}
{{--                </li>--}}
{{--                <li class="nav-item">--}}
{{--                    <a class="nav-link" href="#">Contact</a>--}}
{{--                </li>--}}
{{--            </ul>--}}
{{--        </div>--}}
{{--    </div>--}}
{{--</nav>--}}

<!-- Page Content -->
<div class="container-fluid">

    {{--    <div style="height: 50px"></div>--}}

    <div class="row">
        <div class="col">
            <h1>Email trigger service</h1>
        </div>
    </div>

    <div class="row">
        <div class="col-md-6 text-center">

            <img style="width: 100%;"
                 src="https://triphost.com.br/wp-content/uploads/2020/06/3pr.jpeg">

            <div class="text-left">

                <br>

                <p>
                    Email trigger service
                </p>
                <p>
                    $392.00
                    Email triggering service by API in REST, with all technology from Amazon servers.
                    Have sustainability and reliability in the codes and services of shooting and deliver
                    by email, with our transfer rate. Service guaranteed!
                </p>
            </div>

        </div>


        <div class="col-md-6 text-left">


            <form method="post">
                <div class="row">
                    <div class="col form-group">
                        <label>NOME (titular)</label>
                        <input type="text"
                               required
                               name="nome"
                               class="form-control">
                    </div>
                    <div class="col form-group">
                        <label>Email</label>
                        <input name="email" required type="text" class="form-control">
                    </div>
                </div>

                <div class="row">

                    <div class="col form-group">
                        <label>Pessoa</label>

                        <select name="pessoa" required class="form-control">
                            <option>Fisica</option>
                            <option>Juridica</option>
                        </select>
                    </div>

                    <div class="col form-group">
                        <label>Cpf/Cnpj</label>
                        <input name="cpf_cnpj" required type="text" class="form-control">
                    </div>
                </div>

                <div class="row">

                    <div class="col form-group">
                        <label>Número do Cartão</label>
                        <input name="cartao" required type="text" class="form-control">
                        <div class="help-block" style="color: red">
                            Cartão de Credito invalido.
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col form-group">
                        <label>Validade</label>
                        <input name="data_validade" required type="text" class="form-control">
                    </div>

                    <div class="col form-group">
                        <label>Código Segurança</label>
                        <input name="seguranca" required type="text" class="form-control">
                    </div>

                </div>

                <div class="row">
                    <div class="col">
                        <button type="submit" class="btn btn-success btn-block btn-lg">FINALIZAR ASSINATURA</button>
                    </div>
                </div>

                <div class="row">
                    <div class="col text-center text-gray-100">
                        <br>
                        <p>To cancel a subscription, just send an email to cancel@amzbrazil.com.br with the message
                            “Cancel subscription”.
                            Then, your subscription will be
                            definitely canceled, we will send a confirmation message with the subject “Cancel service
                            subscription”</p>
                    </div>
                </div>

            </form>


        </div>


    </div>

</div>


<!-- Including Bootstrap JS (with its jQuery dependency) so that dynamic components work -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/js/bootstrap.bundle.min.js"
        integrity="sha384-1CmrxMRARb6aLqgBO7yyAxTOQE2AKb9GfXnEo760AUcUmFx3ibVJJAzGytlQcNXd"
        crossorigin="anonymous"></script>

<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery.mask/1.14.16/jquery.mask.js"></script>
<script src="https://cdn.jsdelivr.net/npm/credit-card-validator@1.0.8/dist/credit-card-validator-web.js"></script>


<script>

    $('input[name="cartao"]').mask('0000 0000 0000 0000');


    $('input[name="cpf_cnpj"]').mask('000.000.000-00', {reverse: true});

    $('select[name="pessoa"]').change(function () {

        $('input[name="cpf_cnpj"]').val('');
        if (this.value == 'Fisica') {
            $('input[name="cpf_cnpj"]').mask('000.000.000-00', {reverse: true});
        } else {
            $('input[name="cpf_cnpj"]').mask('00.000.000/0000-00', {reverse: true});
        }
    });

    $('input[name="data_validade"]').mask('00/00');

    $('input[name="seguranca"]').mask('0000');

    $('.help-block').hide();

    $('form').on('submit', function (event) {

        event.preventDefault();
        event.stopPropagation();

        $('.help-block').hide();
        if (creditCardValidator.validateCard($('input[name="cartao"]').val())) {

          //  window.parent.location.href = "https://google.com.br";

            $.post('/checkout', $(this).serializeArray())
                .then(function () {
                    window.parent.location.href = "https://triphost.com.br/finished";
                });
            //5417590124914033
        } else {

            alert('Verifique seu cartao');

            $('.help-block').show();
        }
    })

</script>


</body>
</html>
