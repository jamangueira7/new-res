<?php
 session_start();
 require('_app/Config.inc.php');

 $login    = new Login;
 if (!$login->CheckLogin()):
    unset($_SESSION['userlogin']);
    header('Location: index.php?exe=restrito');
    die;
 else:
    $userlogin = $_SESSION['userlogin'];
 endif;

 $logoff   = filter_input(INPUT_GET, 'logoff', FILTER_VALIDATE_BOOLEAN);
 if ($logoff):
    unset($_SESSION['userlogin']);
    header('Location: index.php?exe=logoff');
    die;
 endif;

 #--- AUTO INSTANCE OBJECT READ
 if (empty($Read))
    $Read = new Read;

 #--- AUTO INSTANCE OBJECT CREATE
 if (empty($Create))
    $Create = new Create;

 #--- AUTO INSTANCE OBJECT UPDATE
 if (empty($Update))
    $Update = new Update;

 #--- AUTO INSTANCE OBJECT DELETE
 if (empty($Delete))
    $Delete = new Delete;
?>
<!DOCTYPE html>
<html>
 <head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
  <title><?= $itwvParameters[1]; ?></title>
  <link rel="shortcut icon" href="img/favicon.png" />

  <link rel="stylesheet" href="_cdn/bower_components/bootstrap/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="_css/jquery-ui.css">
  <link rel="stylesheet" href="_css/painel.css"/>
  <link rel="stylesheet" href="_cdn/bower_components/select2/dist/css/select2.min.css">
  <link rel="stylesheet" href="_cdn/bower_components/font-awesome/css/font-awesome.min.css">
  <link rel="stylesheet" href="_cdn/bower_components/Ionicons/css/ionicons.min.css">
  <link rel="stylesheet" href="dist/css/AdminLTE.min.css">
  <link rel="stylesheet" href="_css/lightbox.css" />
  <link rel="stylesheet" href="_cdn/bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css">
  <link rel="stylesheet" href="dist/css/skins/skin-green.min.css">
  <link rel='stylesheet' href='_css/fonts.css' type='text/css'>

  <!-- REQUIRED JS SCRIPTS -->
  <script src="_cdn/bower_components/jquery/dist/jquery.min.js"></script>
  <script src="_cdn/bower_components/bootstrap/dist/js/bootstrap.min.js"></script>
  <script src="dist/js/adminlte.min.js"></script>
  <script src="_js/scripts.js"></script>
  <script src="_cdn/jquery-ui.min.js"></script>
  <script src="_cdn/bower_components/select2/dist/js/select2.full.min.js"></script>
  <script src="_cdn/bower_components/datatables.net/js/jquery.dataTables.min.js"></script>
  <script src="_cdn/bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js"></script>
  <script src="_cdn/bower_components/tablesorter/jquery.tablesorter.js"></script>
  <!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.5.0/Chart.min.js"></script> -->
  <!-- <script src="_cdn/bower_components/chart.js/Chart.js"></script> -->
  <script src="_cdn/chart/src/chart.js"></script>
  <script src="_cdn/lightbox.js"></script>
  <script src="_cdn/jquery.mask.js"></script>
  <script src="_js/Mascaras.js"></script>
 </head>

 <body class="hold-transition skin-green sidebar-mini">
    <?php require('modais.php'); ?>

    <div class="wrapper">
        <header class="main-header">
            <a href="?" class="logo">
                <span class="logo-mini"><?php for ($iR = 0; $iR < count($itwvParameters['name_fantasia']); $iR++) echo "<b>{$itwvParameters['name_fantasia'][$iR][0]}</b>"; ?></span>
                <span class="logo-lg"><?= str_replace($itwvParameters['name_fantasia'][0], "<b>{$itwvParameters['name_fantasia'][0]}</b>", $itwvParameters[1]) ?></span>
            </a>
            <nav class="navbar navbar-static-top" role="navigation">
                <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button" title='<?= $LANG['menu']['menu']; ?>'>
                    <span class="sr-only"><?= $LANG['menu']['menu']; ?></span>
                </a>
                <div class="navbar-custom-menu">
                  <ul class="nav navbar-nav">
					<li class="dropdown user user-menu">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?= ((!empty($userlogin['foto'])) ? "imagens/fotos/{$userlogin['foto']}" : 'img/default.png') ?>" class="user-image" title="<?= mb_convert_case($userlogin['nome'], MB_CASE_UPPER)?>">
                            <span class="hidden-xs"><?= mb_convert_case($userlogin['nome'], MB_CASE_UPPER)?></span>
                        </a>
                        <ul class="dropdown-menu">
                            <li class="user-header">
                                <img src="<?= ((!empty($userlogin['foto'])) ? "imagens/fotos/{$userlogin['foto']}" : 'img/default.png') ?>" class="img-circle" title="<?= mb_convert_case($userlogin['nome'], MB_CASE_UPPER)?>">
                                <p><?= mb_convert_case($userlogin['nome'], MB_CASE_UPPER). '<br />' . $LANG['geral']['registered']  .': '. date('d/m/Y', strtotime($userlogin['data_hora']))?></p>
                            </li>
                            <li class="user-footer">
                                <div class="pull-left">
                                  <a href="painel.php?pag=perfil/view" class="btn btn-default btn-flat"><?= $LANG['modulos']['perfil']; ?></a>
                                </div>
                                <div class="pull-right">
                                  <a href="painel.php?logoff=true" class="btn btn-default btn-flat"><?= $LANG['button']['exit']; ?></a>
                                </div>
                            </li>
                        </ul>
                    </li>
                </ul>
            </div>
        </nav>
    </header>
    <aside class="main-sidebar">
        <section class="sidebar">
            <?php require('menu.php'); ?>
        </section>
    </aside>
    <div class="content-wrapper">
     <section class="content-header" <?= ((empty($HeaderModulo)) ? 'style="display: none;"' : '') ;?>>
      <h1>
       <span class="upercase"><?= (!empty($HeaderModulo) ? $HeaderModulo : $LANG['geral']['main_panel']); ?></span>
       <small><?= (!empty($HeaderVersao) ? $LANG['geral']['version'] : ''); ?>&nbsp;<?= $HeaderVersao ?></small>
      </h1>
      <ol class="breadcrumb">
       <li><i class="fa <?= $HeaderIcon ?>"></i>&nbsp;&nbsp;<?= $HeaderMenu ?></li>
       <?= (!empty($HeaderModulo) ? '<li class="active">'. $HeaderModulo .'</li>' : ''); ?>
       <?= ((isset($idUpdate)) ? '<li class="active">'.$LANG['geral']['update'].'</li>' : $HeaderCreate) ?>
      </ol>
     </section>
     <?php
      //QUERY STRING
      if (!empty($getexe)):
       $includepatch = __DIR__ . DIRECTORY_SEPARATOR . '_modulos' . DIRECTORY_SEPARATOR . strip_tags(trim($getexe) . '.php');
      else:
       $includepatch = __DIR__ . DIRECTORY_SEPARATOR . 'home.php';
      endif;

      if (file_exists($includepatch))
       require_once($includepatch);
     ?>
    </div>
    <footer class="main-footer">
        <div class="pull-right hidden-xs"><?=$LANG['geral']['version']?> 1.0</div>
        <strong>Copyright &copy; <?= date('Y'); ?> <a href="http://www.itwv.com.br/" class='text-red' target='_blank'>ITWV</a>.</strong> All rights reserved.
    </footer>
    <div class="control-sidebar-bg"></div>
  </div>

 </body>
</html>
