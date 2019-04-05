<!DOCTYPE html>
<html>
    <head>
        @include('templates.head')
        {{--CSS DO ESTILO--}}
        <link rel="stylesheet" href="{{ asset('css/skin-green.min.css') }}">
    </head>

    <body class="hold-transition skin-green sidebar-mini">
        <div class="wrapper">
            {{--HEADER DO SISTEMA--}}
            @include('templates.header')

            {{--MENU LATERAL--}}
            <aside class="main-sidebar">
                @include('templates.menu-lateral')
            </aside>

            {{--CONTEUDO DO SISTEMA--}}
            <div class="content-wrapper">
                <section id="content-header" class="content-header">
                    @yield('conteudo-view')
                </section>
            </div>

            {{--RODAPÉ DO SISTEMA--}}
            @include('templates.footer')
        </div>
        @yield('js-view')
    </body>
</html>
