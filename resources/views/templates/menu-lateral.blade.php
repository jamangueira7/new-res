<section class="sidebar">
    <div class="user-panel">
        <div class="pull-left image">
            <img src="img/default.png" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
            <p>Usuario</p>
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
                    <a href="#">
                        <i class="fa fa-user"></i>Usuário
                    </a>
                </li>
            </ul>
        </li>
        {{--MENU RELATORIOS--}}
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
                    <a href="#">
                        <i class="fa fa-group"></i>Log de acesso
                    </a>
                </li>
                {{--SUBMENU RELATORIOS->CRITICAS--}}
                <li class="">
                    <a href="#">
                        <i class="fa fa-exclamation-triangle"></i>Críticas
                    </a>
                </li>
                {{--SUBMENU RELATORIOS->TRANSAÇÃO--}}
                <li class="">
                    <a href="#">
                        <i class="fa fa-exchange"></i>Transação
                    </a>
                </li>
            </ul>
        </li>
        {{--MENU CONFIGURAÇÕES--}}
        <li class="treeview">
            <a href="#"><i class="fa fa-gears"></i>
                <span>Configuração</span>
                <span class="pull-right-container">
                    <i class="fa fa-angle-left pull-right"></i>
                </span>
            </a>
            <ul class="treeview-menu">
                {{--SUBMENU CONFIGURAÇÕES->NIVEIS DE ACESSO--}}
                <li class="">
                    <a href="#">
                        <i class="fa fa-level-up"></i>Níveis de acesso
                    </a>
                </li>
                {{--SUBMENU CONFIGURAÇÕES->PARAMETROS--}}
                <li class="">
                    <a href="#">
                        <i class="fa fa-quote-left"></i>Parâmetros
                    </a>
                </li>
                {{--SUBMENU CONFIGURAÇÕES->PERFIL--}}
                <li class="">
                    <a href="#">
                        <i class="fa fa-user"></i>Peril
                    </a>
                </li>
                {{--SUBMENU CONFIGURAÇÕES->PERMISSÕES--}}
                <li class="">
                    <a href="#">
                        <i class="fa fa-hand-stop-o"></i>Permissões
                    </a>
                </li>
            </ul>
        </li>
    </ul>
</section>
