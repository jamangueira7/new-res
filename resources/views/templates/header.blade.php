<header class="main-header">
    <a href="?" class="logo">
        <span class="logo-mini"><b>U</b><b>P</b></span>
        <span class="logo-lg"><b>UNIMED</b> PARANÁ</span>
    </a>
    <nav class="navbar navbar-static-top" role="navigation">
        <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" title=''>
            <span class="sr-only">Menu</span>
        </a>
        {{--DADOS MENU--}}
        <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
                <li class="dropdown user user-menu">
                    <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                        <img src="{{URL::asset('/img/default.png')}}" class="user-image" title="nomeUser">
                        <span class="hidden-xs">{{Session::get('login')['name']}}</span>
                    </a>
                    <ul class="dropdown-menu">
                        <li class="user-header">
                            <img src="{{URL::asset('/img/default.png')}}" class="img-circle" title="nomeUser">
                            <p>{{Session::get('login')['name']}}</p>
                        </li>
                        <li class="user-footer">
                            <div class="pull-left">
                                <a href="{{route('user.edit', Session::get('login')['id'])}}">Pefil</a>
                            </div>
                            <div class="pull-right">
                                <a href="{{route('user.logout')}}" class="btn btn-default btn-flat">Sair</a>
                            </div>
                        </li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>
</header>
