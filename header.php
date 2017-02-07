<?
require_once 'config/config.php';
if(isset($_GET['ver'])){ $inicio = $_GET['ver']; }else{ $inicio = ""; }
if(isset($_GET['opc'])){ $opc = $_GET['opc']; }else{ $opc = ""; }
if(isset($_GET['id'])){ $id = $_GET['id']; }else{ $id = 0; }

if($_SESSION['nivel']=='A'){ $nivel = "Administrador";}else{ $nivel = "Colaborador";}
if($_SESSION['user_sexo']=='M'){ $ClassSex="fa fa-user";}else{ $ClassSex= "fa fa-female";}
?>
<!DOCTYPE html>
<html lang="ES-es">
  <head>
    <title>SIPRE</title>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <!-- Tell the browser to be responsive to screen width -->
    <?=Base::Meta("viewport","width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no")?>
    
    <!-- Bootstrap 3.3.5 -->
    <?=Base::Css("includes/css/bootstrap.min.css")?>

    <?=Base::Css("includes/css/bootstrap-switch.css")?>

    <!-- Font Awesome -->
    <?=Base::Css("includes/css/font-awesome.min.css")?>
    <?=Base::Css("includes/css/glyphicons.css")?>
    <?=Base::Css("includes/css/styles.css")?>
    <?=Base::Css("plugins/daterangepicker/daterangepicker.css")?>

    <!-- AdminLTE Skins. Choose a skin from the css/skins
        folder instead of downloading all of them to reduce the load. -->
    <?=Base::Css("includes/css/_all-skins.min.css")?>
    <!-- Select 2 -->
    <?=Base::Css("plugins/select2/select2.min.css")?>
    <!-- Theme style -->
    <?=Base::Css("includes/css/AdminLTE.min.css")?>

    <!-- Datatable -->
    <?=Base::Css("plugins/datatables/dataTables.bootstrap.css")?>

    <?=Base::Js("includes/js/jquery-2.2.1.min.js")?>
    <?=Base::Js("includes/js/bootstrap.js")?>
    <?=Base::Js("includes/js/bootstrap-switch.min.js")?>
    
    <!-- Datatable -->
    <?=Base::Js("plugins/datatables/jquery.dataTables.min.js")?>
    <?=Base::Js("plugins/datatables/dataTables.bootstrap.min.js")?>

    <?=Base::Js("includes/js/highcharts.js")?>
    <!-- AdminLTE App -->
    <?=Base::Js("includes/js/app.min.js")?>
    
    <?=Base::Js("plugins/daterangepicker/moment.min.js")?>
    <?=Base::Js("plugins/daterangepicker/daterangepicker.js")?>

    <!-- Funciones -->
    <?=Base::Js("includes/js/funciones.js")?>
  </head>

  <body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">
      <header class="main-header">
        <!-- Logo -->
        <a href="inicio.php" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels --><!--
          <span class="logo-mini"><b>P</b>TILLA</span>
          -->
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><b>SIPRE</b></span>
        </a>

        <!-- Header Navbar: style can be found in header.less -->
        <nav class="navbar navbar-static-top" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Navegación</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
              <!-- Messages: style can be found in dropdown.less-->
              <!-- User Account: style can be found in dropdown.less -->
              <li class="dropdown user user-menu">
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- <img src="dist/img/user2-160x160.jpg" class="user-image" alt="User Image"> -->
              <span><?=$_SESSION['nombre']?></span>
            </a>
            <ul class="dropdown-menu">
              <!-- User image -->
              <li class="user-header">
                <!--<img src="dist/img/user2-160x160.jpg" class="img-circle" alt="User Image">-->
                <p>
                  <?=$_SESSION['email']?><br>
                  <strong>Cedula: </strong><?=$_SESSION['cedula']?><br>
                  <strong><?=$nivel?></strong>
                </p>
              </li>
              <!-- Menu Footer-->
              <li class="user-footer">
                <div class="pull-left">
                  <a href="?ver=perfil" class="btn btn-default btn-flat"><i class="<?=$ClassSex?>" aria-hidden="true"></i> Mi Perfil</a>
                </div>
                <div class="pull-right">
                  <a id="b-logout" href="#" class="btn btn-default btn-flat"><i class="fa fa-sign-out" aria-hidden="true"></i> Salir</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
            </ul>
          </div>
        </nav>
      </header>
      <!-- Left side column. contains the logo and sidebar -->
      <aside class="main-sidebar">
        <!-- sidebar: style can be found in sidebar.less -->
        <section class="sidebar">
          <!-- Sidebar user panel -->
                    
          <!-- sidebar menu: : style can be found in sidebar.less -->
          <ul class="sidebar-menu">
            <li class="header">MENU</li>
            <? if($_SESSION['nivel'] == "A"): ?>
            <li class="treeview <?=($inicio=="usuarios")?'active':'';?>">
              <a href="#">
                <i class="fa fa-users"></i>
                <span>Usuarios</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="?ver=usuarios"><i class="fa fa-circle-o"></i>Ver Usuarios</a></li>
                <li><a href="?ver=usuarios&opc=add"><i class="fa fa-circle-o"></i>Agregar Usuario</a></li>
              </ul>
            </li>
            <? endif; ?>

            <li class="treeview <?=($inicio=="electores")?'active':'';?>">
              <a href="#">
                <i class="fa fa-address-book-o"></i>
                <span>Electores</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu">
                <li><a href="?ver=electores"><i class="fa fa-circle-o"></i>Ver Electores</a></li>
                <li><a href="?ver=electores&opc=add"><i class="fa fa-circle-o"></i>Agregar Elector</a></li>
                <li><a href="?ver=electores&opc=cargar"><i class="fa fa-circle-o"></i>Carga Masiva</a></li>
                <li><a href="?ver=electores&opc=consulta"><i class="fa fa-circle-o"></i>Consultas</a></li>
              </ul>
            </li>

            <li class="treeview <?=($inicio=="centros")?'active':'';?>">
              <a href="#">
                <i class="fa fa-home" aria-hidden="true"></i>
                <span>Centros de votacion</span>
                <i class="fa fa-angle-left pull-right"></i>
              </a>
              <ul class="treeview-menu menu-open">
                <li><a href="?ver=centros"><i class="fa fa-circle-o"></i>Ver Centros</a></li>
                <li><a href="?ver=centros&opc=add"><i class="fa fa-circle-o"></i>Agregar Centros</a></li>
              </ul>
            </li>

            <li class="<?=($inicio=="sectores")?'active':'';?>">
              <a href="?ver=sectores">
                <i class="fa fa-map-marker"></i>
                <span>Sectores</span>
              </a>
            </li>

            <li>
              <a href="?ver=electores&opc=consulta">
                <i class="fa fa-search" aria-hidden="true"></i>
                <span>Consultas</span>
              </a>
            </li>
                        
          </ul>
        </section>
        <!-- /.sidebar -->
      </aside>
      <!--Contenido TODO LO DE EL MEDIO -->
      <!-- Content Wrapper. Contains page content -->
      <div class="content-wrapper">      
        <!-- Main content -->
