<!DOCTYPE html>
<html>
    <head>
        @include('templates.head')
        <link rel="stylesheet" href="{{ asset('css/relatorio.css') }}">

    </head>

    <body class="hold-transition skin-green sidebar-mini">
        <div class="container">
            <div class="content">
                @include('templates.report.header-report')
                @yield('conteudo-report')
            </div>
        </div>
        @yield('js-view')
    </body>
</html>
