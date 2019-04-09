<!DOCTYPE html>
<html lang="pt-br">
    <head>
        <meta charset="UTF-8">
        <title>Pagina</title>
        <meta name="csrf-token" content="{{ csrf_token() }}" />
        <meta name="description" content="//"/>
        <meta name="viewport" content="width=device-width,initial-scale=1,maximum-scale=1,user-scalable=0"/>
        <meta name="robots" content="noindex, nofollow"/>

        <link rel="shortcut icon" href="{{ asset('img/favicon.png') }}" />

        <link rel='stylesheet' href="{{ asset('css/fonts.css') }}" type='text/css'>
        <link rel='stylesheet' href="{{ asset('css/reset.css') }}" type='text/css'>
        <link rel='stylesheet' href="{{ asset('css/login.css') }}" type='text/css'>
        <link rel='stylesheet' href="{{ asset('css/alerts.css') }}" type='text/css'>

        <!-- DataTables -->
        <link rel="stylesheet" href="{{ asset('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    </head>

    <body class="login">

            <div class="container login_container">
                <div class="login_box">
                    @if(!empty(session('error')['error']))
                        <div class="alert alert-danger" role="alert">
                            {{session('error')['messages']}}
                        </div>
                    @endif
                    <div class="name_project">
                        <img src="{{ asset('img/img-fundo-relatorios.jpg') }}" alt="Logar" title="Logar" width="130">
                    </div>

                    {!! Form::open(['route'=> 'user.login','method' => 'post', 'class' => 'login_form']) !!}
                        <label class="label">
                            <span class="legend">E-mail:</span>
                            <input class="email" type="text" name="email" value="" placeholder="E-mail" required/>
                        </label>

                        <label class="label">
                            <span class="legend">Senha:</span>
                            <input type="password" name="pass" placeholder="Senha" required/>
                        </label>

                        <button class="btn btn_green " value="AdminLogin" name="AdminLogin" type="submit">Entrar!</button>
                    {!! Form::close() !!}

                </div>
            </div>

            <div class="login_bg"></div>


            <script src="{{ asset('js/jquery.js') }}"></script>

            <script src="{{ asset('js/jquery-ui.min.js') }}"></script>
            <script src="{{ asset('bower_components/select2/dist/js/select2.full.min.js') }}"></script>

            <script src="{{ asset('js/jquery.mask.js') }}"></script>
            <script src="{{ asset('js/Mascaras.js') }}"></script>
            <script src="{{ asset('js/scripts.js') }}"></script>
            <!-- DataTables -->
            <script src="{{ asset('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
            <script src="{{ asset('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>
            <script src="{{ asset('bower_components/tablesorter/jquery.tablesorter.js') }}"></script>

    </body>
</html>

