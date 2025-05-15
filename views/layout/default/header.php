<!DOCTYPE html>
<html lang="en">
  <head>
    <link id="page_favicon" href="<?php echo $_layoutParam['route_img']?>logos/favicon.ico" rel="icon" type="image/x-icon" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <title><?php echo $this->titulo . " | "; echo $_layoutParam['titulo_principal']; ?></title>
    <link href="<?php echo $_layoutParam['route_css'] ?>bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $_layoutParam['route_css'] ?>londinium-theme.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $_layoutParam['route_css'] ?>styles.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $_layoutParam['route_css'] ?>icons.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jquery/1.10.1/jquery.min.js"></script>
    <script type="text/javascript" src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.10.2/jquery-ui.min.js"></script>
  </head>
  <body >

    <!-- Encabezado principal -->
    <div class="navbar navbar-inverse" role="navigation">
      <div class="navbar-header">
        <a class="navbar-brand" href="javascript:void(0);" onclick="location.href='<?php echo BASE_URL?>'">
  				<object data="<?php echo $_layoutParam['route_img']?>logos/logo.svg" width="100%" height="100%" type="image/svg+xml" id="contenedorSvg1"></object>
  			</a>
        <a class="sidebar-toggle"><i class="icon-paragraph-justify2"></i></a>
        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#navbar-icons">
          <span class="sr-only">Abrir Barra</span>
          <i class="icon-grid3"></i>
        </button>
        <!--Botón de navación versión móvil -->
        <button type="button" class="navbar-toggle offcanvas">
          <span class="sr-only">Barra de navegación?</span>
          <i class="icon-paragraph-justify"></i>
        </button>
        <!--Fin botón de navegación versión móvil -->
      </div>

      <ul class="nav navbar-nav navbar-right collapse" id="navbar-icons">
        <li class="dropdown">
          <a class="dropdown-toggle" data-toggle="dropdown">
            <i class="icon-people"></i><span class="label label-default">2</span>
          </a>
          <div class="popup dropdown-menu dropdown-menu-right">
            <div class="popup-header">
              <a href="#" class="pull-left"><i class="icon-spinner7"></i></a>
              <span>Actividad</span>
              <a href="#" class="pull-right"><i class="icon-paragraph-justify"></i></a>
            </div>
            <ul class="activity">
              <li>
                <i class="icon-cart-checkout text-success"></i>
                <div>
                  <a href="#"><?php echo ($_SESSION['usuario']);?></a> ordered 2 copies of <a href="#">OEM license</a>
                  <span>14 minutes ago</span>
                </div>
              </li>
          </ul>
        </div>
      </li>
      <li class="dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown">
          <i class="icon-paragraph-justify2"></i>
          <span class="label label-default">6</span>
        </a>
        <div class="popup dropdown-menu dropdown-menu-right">
          <div class="popup-header">
            <a href="#" class="pull-left"><i class="icon-spinner7"></i></a>
            <span>Mensajes</span>
            <a href="#" class="pull-right"><i class="icon-new-tab"></i></a>
          </div>
          <ul class="popup-messages">
            <li class="unread">
              <a href="#">
                <img src="http://placehold.it/300" alt="" class="user-face">
                <strong>Eugene Kopyov <i class="icon-attachment2"></i></strong>
                <span>Aliquam interdum convallis massa...</span>
              </a>
            </li>
            <li>
              <a href="#">
                <img src="http://placehold.it/300" alt="" class="user-face">
                <strong>Jason Goldsmith <i class="icon-attachment2"></i></strong>
                <span>Aliquam interdum convallis massa...</span>
              </a>
            </li>
            <li>
              <a href="#">
                <img src="http://placehold.it/300" alt="" class="user-face">
                <strong>Angel Novator</strong>
                <span>Aliquam interdum convallis massa...</span>
              </a>
            </li>
            <li>
              <a href="#">
                <img src="http://placehold.it/300" alt="" class="user-face">
                <strong>Monica Bloomberg</strong>
                <span>Aliquam interdum convallis massa...</span>
              </a>
            </li>
              <li>
                <a href="#">
                  <img src="http://placehold.it/300" alt="" class="user-face">
                  <strong>Patrick Winsleur</strong>
                  <span>Aliquam interdum convallis massa...</span>
                </a>
              </li>
          </ul>
        </div>
      </li>
      <li class="dropdown">
        <a data-toggle="dropdown" class="dropdown-toggle">
          <i class="icon-grid"></i>
        </a>
        <div class="popup dropdown-menu dropdown-menu-right">
          <div class="popup-header">
            <a href="#" class="pull-left"><i class="icon-spinner7"></i></a>
            <span>Procesos</span>
            <a href="#" class="pull-right"><i class="icon-new-tab"></i></a>
          </div>
          <table class="table table-hover">
            <thead>
              <tr>
                <th>Descripción</th>
                <th>Categoría</th>
                <th class="text-center">Prioridad</th>
              </tr>
            </thead>
            <tbody>
              <tr>
                <td><span class="status status-success item-before"></span> <a href="#">Frontpage fixes</a></td>
                <td><span class="text-smaller text-semibold">Bugs</span></td>
                <td class="text-center"><span class="label label-success">87%</span></td>
              </tr>
              <tr>
                <td><span class="status status-danger item-before"></span> <a href="#">CSS compilation</a></td>
                <td><span class="text-smaller text-semibold">Bugs</span></td>
                <td class="text-center"><span class="label label-danger">18%</span></td>
              </tr>
              <tr>
                <td><span class="status status-info item-before"></span> <a href="#">Responsive layout changes</a></td>
                <td><span class="text-smaller text-semibold">Layout</span></td>
                <td class="text-center"><span class="label label-info">52%</span></td>
              </tr>
              <tr>
                <td><span class="status status-success item-before"></span> <a href="#">Add categories filter</a></td>
                <td><span class="text-smaller text-semibold">Content</span></td>
                <td class="text-center"><span class="label label-success">100%</span></td>
              </tr>
              <tr>
                <td><span class="status status-success item-before"></span> <a href="#">Media grid padding issue</a></td>
                <td><span class="text-smaller text-semibold">Bugs</span></td>
                <td class="text-center"><span class="label label-success">100%</span></td>
              </tr>
            </tbody>
          </table>
        </div>
      </li>
      <li class="user dropdown">
        <a class="dropdown-toggle" data-toggle="dropdown">
          <img src="<?php echo  $_SESSION['avatar'];?>" alt="image-1">
          <span><?php echo ($_SESSION['usuario']);?></span>
          <i class="caret"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-right icons-right">
          <li><a href="#"><i class="icon-user"></i> Perfil</a></li>
          <li><a href="#"><i class="icon-bubble4"></i> Mensajes</a></li>
          <li><a href="#"><i class="icon-cog"></i> Configuración</a></li>
          <li><a href="javascript:void(0)" onclick="location.href='<?php echo BASE_URL;?>login/cerrarSesion/'"><i class="icon-exit"></i> Salir</a></li>
        </ul>
      </li>
    </ul>
  </div>
  <!--/Encabezado principal-->

  <!-- Contenedor de paginas lateral-->
  <div class="page-container">
    <!-- Sidebar -->
    <div class="sidebar">
      <div class="sidebar-content">
        <!-- Información usuario -->
        <div class="user-menu dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown">
            <img src="<?php echo  $_SESSION['avatar'];?>">
            <div class="user-info">
              <?php echo $_SESSION['usuario']?> <span><?php echo $_SESSION['ocupacion'];?></span>
            </div>
          </a>
          <div class="popup dropdown-menu dropdown-menu-right">
            <div class="thumbnail">
              <div class="thumb">
                <img src="<?php echo  $_SESSION['avatarReal'];?>">
                <div class="thumb-options">
                  <span>
                    <a href="#" class="btn btn-icon btn-success"><i class="icon-pencil"></i></a>
                    <a href="#" class="btn btn-icon btn-success"><i class="icon-remove"></i></a>
                  </span>
                </div>
              </div>
              <div class="caption text-center">
                <h6><?php echo $_SESSION['usuario']?> <small>Web Developer</small></h6>
              </div>
            </div>
            <ul class="list-group">
              <li class="list-group-item"><i class="icon-pencil3 text-muted"></i> My posts <span class="label label-success">289</span></li>
              <li class="list-group-item"><i class="icon-people text-muted"></i> Users online <span class="label label-danger">892</span></li>
              <li class="list-group-item"><i class="icon-stats2 text-muted"></i> Reports <span class="label label-primary">92</span></li>
              <li class="list-group-item"><i class="icon-stack text-muted"></i> Balance <h5 class="pull-right text-danger">$45.389</h5></li>
            </ul>
          </div>
        </div>
        <!-- /Información usuario -->
        <?php if(isset($widgets['header'])){
            foreach ($widgets['header'] as $menu) {
              echo $menu;
            }
          };?>
      </div>
    </div>
    <!-- /sidebar -->
  </div>
  <!--/contenedor de pagina lateral -->
  <!-- Contenedor de página principal-->
  <div class="page-content">
    <!-- Encabezado de página -->
    <div class="page-header">
      <div class="page-title">
        <h3><?php echo $this->titulo; ?> <small>Bienvenido <?php echo $_SESSION['usuario']?></small></h3>
      </div>
      <div id="reportrange" class="range">
        <div class="visible-xs header-element-toggle">
          <a class="btn btn-primary btn-icon"><i class="icon-calendar"></i></a>s
        </div>
        <div class="date-range"></div>
        <span class="label label-danger">9</span>
      </div>
    </div>
    <!-- /Encaabezado de página -->
    <!-- migajas de pan -->
    <div class="breadcrumb-line">
      <ul class="breadcrumb">
        <li>
          <a href="javascript:void(0);" onclick="location.href='<?php echo BASE_URL ;?>'">
            Inicio
          </a>
        </li>
        <?php if(isset($this->pronavegacion) && !isset($this->inicio)):?>
          <li class="active">
            <a href="javascript:void(0);" onclick="location.href='<?php echo $this->url?>'">
              <?php echo $this->pronavegacion;?>
            </a>
          </li>
          <?php endif; if(isset($this->navCatalogo)):?>
          <li class="active">
            <a href="javascript:void(0);" onclick="location.href='<?php echo $this->urlCatalogo?>'">
              <?php echo $this->navCatalogo;?>
            </a>
          </li>
        <?php endif;?>
      <?php if(isset($this->navControl)):?>
      <li class="active">
        <a href="javascript:void(0);" onclick="location.href='<?php echo $this->urlIPS?>'">
          <?php echo $this->navControl;?>
        </a>
      </li>
    <?php endif;?>
        <li class="active"><?php echo $this->titulo; ?></li>
      </ul>
      <div class="visible-xs breadcrumb-toggle">
        <a class="btn btn-link btn-lg btn-icon" data-toggle="collapse" data-target=".breadcrumb-buttons"><i class="icon-menu2"></i></a>
      </div>
      <ul class="breadcrumb-buttons collapse">
        <li class="dropdown">
          <a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-search3"></i> <span>Buscar</span> <b class="caret"></b></a>
          <div class="popup dropdown-menu dropdown-menu-right">
            <div class="popup-header">
              <a href="#" class="pull-left"><i class="icon-paragraph-justify"></i></a>
              <span>Busqueda rápida</span>
              <a href="#" class="pull-right"><i class="icon-new-tab"></i></a>
            </div>
            <form action="#" class="breadcrumb-search">
              <input type="text" placeholder="Type and hit enter..." name="search" class="form-control autocomplete">
              <div class="row">
                <div class="col-xs-6">
                  <label class="radio">
                    <input type="radio" name="search-option" class="styled" checked="checked">
                    Everywhere
                  </label>
                  <label class="radio">
                    <input type="radio" name="search-option" class="styled">
                    Invoices
                  </label>
                </div>
                <div class="col-xs-6">
                  <label class="radio">
                    <input type="radio" name="search-option" class="styled">
                    Users
                  </label>
                  <label class="radio">
                    <input type="radio" name="search-option" class="styled">
                    Orders
                  </label>
                </div>
              </div>
              <input type="submit" class="btn btn-block btn-success" value="Search">
            </form>
          </div>
        </li>
      </ul>
    </div>
    <!-- /Migajas de pan -->
