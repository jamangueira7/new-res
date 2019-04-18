<section class="sidebar">
    <div class="user-panel">
        <div class="pull-left image">
            <img src="{{URL::asset('/img/default.png')}}" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p>{{session('login')['name']}}</p>
            <a href="#"><i class="fa fa-circle text-success"></i> Conectado</a>
        </div>
    </div>
    {{--###########ITENS MENU###########--}}
    {{--MENU CADASTRO--}}
    <ul class="sidebar-menu" data-widget="tree">
        <li class="treeview active">
            <a href="#"><i class="fa fa-edit"></i>
                <span>Cadastro</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                <li class="active">
                    <a href="{{route('user.index')}}">
                        <i class="fa fa-user"></i>Usuário
                    </a>
                </li>
                @if(session('login')['level_id'] != 3)
                    <li >
                        <a href="{{route('consent.index')}}">
                            <i class="fa fa-user"></i>Consentimento
                        </a>
                    </li>
                @endif
            </ul>
        </li>
        {{--MENU RELATORIOS--}}
        @if(session('login')['level_id'] != 2)
            <li class="treeview">
                <a href="#"><i class="fa fa-line-chart"></i>
                    <span>Relatórios</span>
                    <span class="pull-right-container">
                        <i class="fa fa-angle-left pull-right"></i>
                    </span>
                </a>
                <ul class="treeview-menu">
                    {{--SUBMENU RELATORIOS->LOG ACESSO--}}
                    <li class="">
                        <a href="{{route('report.index')}}">
                            <i class="fa fa-group"></i>Log de acesso
                        </a>
                    </li>
                    {{--SUBMENU RELATORIOS->CRITICAS--}}
                    <li class="">
                        <a href="{{route('report.review')}}">
                            <i class="fa fa-exclamation-triangle"></i>Críticas
                        </a>
                    </li>
                    {{--SUBMENU RELATORIOS->TRANSAÇÃO--}}
                    <li class="">
                        <a href="{{route('report.transaction')}}">
                            <i class="fa fa-exchange"></i>Transação
                        </a>
                    </li>
                </ul>
            </li>
        @endif
    </ul>
</section>
