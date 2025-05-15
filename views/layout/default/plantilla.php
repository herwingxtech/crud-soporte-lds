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
    <link href="<?php echo $_layoutParam['route_css'] ?>/plugins/sweetalert.css" rel="stylesheet" type="text/css">
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
              <?php echo $_SESSION['usuario'] ?> <span><?php echo $_SESSION['ocupacion'];?></span>
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
          <a class="btn btn-primary btn-icon"><i class="icon-calendar"></i></a>
        </div>
        <div class="date-range"></div>
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
      <?php if(isset($this->navRelacion)):?>
      <li class="active">
        <a href="javascript:void(0);" onclick="location.href='<?php echo $this->urlRelacion?>'">
          <?php echo $this->navRelacion;?>
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
              <input type="submit" class="btn btn-block btn-success" value="Buscar">
            </form>
          </div>
        </li>
      </ul>
    </div>
    <!-- /Migajas de pan -->
    <!-- Alertas -->
    <?php if(isset($this->alerta)){ ?>
    <div class="callout callout-<?php echo $this->alerta;?> fade in">
      <button type="button" class="close" data-dismiss="alert">×</button>
      <h5><?php if(isset($this->_mensaje)){ echo $this->_mensaje;} ?></h5>
    </div>    
    <?php

    }
    if(isset($this->jGS)){
      echo $this->jGS;
    }
    ?>
  <!-- /Alertas -->
    <!-- Contenedor dinámico de páginas-->
    <?php
        include_once($route_view);
    ?>
    <!-- /Contenedor dinámico de páginas --> 
      <!-- Pie de pagina -->
      <div class="footer clearfix">
        <div class="pull-left">&copy; <?php echo date('Y');?>. Línea digital Del Sureste,todos los derechos reservados... </div>
        <div class="pull-right icons-group">
          <a href="javascript:void(0)">I.S.C. Pablo Manga Pérez</a>
        </div>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/charts/sparkline.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>/plugins/forms/uniform.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>/plugins/forms/select2.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/inputmask.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/autosize.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/inputlimit.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/listbox.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/multiselect.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/validate.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/tags.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/switch.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/uploader/plupload.full.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/uploader/plupload.queue.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/wysihtml5/wysihtml5.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/wysihtml5/toolbar.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/interface/daterangepicker.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/interface/fancybox.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/interface/moment.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/interface/jgrowl.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/interface/datatables.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/interface/colorpicker.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/interface/fullcalendar.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/interface/timepicker.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/sweetalert.min.js"></script>
        <!--<script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/sweetaler2.min.js"></script> -->
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>application.js"></script>
	<script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>jquery.snow.js"></script>
        <?php
        if(isset($_layoutParam['js'])&& count($_layoutParam['js'])){
          for ($i=0; $i < count((array)$_layoutParam['route_js']); $i++) { 
           ?>
            <script type="text/javascript" src="<?php echo $_layoutParam['js'][$i];?>"></script>
           <?php 
          }
        }
        ?>
      </div>
        <!-- /Pie de pagina -->
    </div>
    <!-- /contenedor de pagina principal-->
  </body>
</html>
