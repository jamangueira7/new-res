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
                    {{--MENSAGENS AO USUARIO--}}
                   @if(!empty(session('success')['success']))
                       <div class="alert alert-success" role="alert">
                           {{session('success')['messages']}}
                       </div>
                   @endif
                   @if(!empty(session('error')['error']))
                       <div class="alert alert-danger" role="alert">
                           {{session('error')['messages']}}
                       </div>
                   @endif
                   @yield('conteudo-view')
                </section>
            </div>

            {{--RODAPÉ DO SISTEMA--}}
            @include('templates.footer')
        </div>
        @yield('js-view')
    </body>
</html>
