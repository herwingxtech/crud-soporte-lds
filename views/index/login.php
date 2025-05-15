<!DOCTYPE html>
<html lang="es">
   <head>
    <link id="page_favicon" href="<?php echo $_layoutParam['route_img']?>logos/favicon.ico" rel="icon" type="image/x-icon" />
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
    <title>
        <?php  echo $this->titulo . " | "; echo $_layoutParam['titulo_principal'] ;?>
    </title>
    <link href="<?php echo $_layoutParam['route_css']?>bootstrap.min.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $_layoutParam['route_css']?>londinium-theme.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $_layoutParam['route_css']?>styles.css" rel="stylesheet" type="text/css">
    <link href="<?php echo $_layoutParam['route_css']?>icons.css" rel="stylesheet" type="text/css">
    <link href="http://fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&amp;subset=latin,cyrillic-ext" rel="stylesheet" type="text/css">
    <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>jqueryoffline/jquery.min.js"></script>
    <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>jqueryoffline/jquery-ui.min.js"></script>

</head>

<body class="full-width page-condensed">
    <!-- Barra Logo-->
    <div class="navbar navbar-inverse" role="navigation">
        <div class="navbar-header">
            <a class="navbar-brand" href="javascript:void(0);" onclick="location.href='<?php echo BASE_URL?>'">
            <object data="<?php echo $_layoutParam['route_img']?>logos/logo.svg" width="100%" height="100%" type="image/svg+xml" id="contenedorSvg1"></object>
         </a>
        </div>
    </div>
    <!-- /Barra Logo-->
    <?php
     /*-- Contenedor dinámico de página--*/
        include_once($route_view);
     /*-- /Contenedor dinámico de página--*/
    ?>
    <!-- Pie de página -->
    <div class="footer clearfix">
        <div class="pull-left">&copy;
            <?php echo date('Y');?>. Línea Digital Del Sureste</div>
        <div class="pull-right icons-group">
            <a href="javascript:void(0)">I.S.C. Pablo Manga Pérez</a>
        </div>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>/plugins/forms/select2.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/inputmask.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/autosize.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/inputlimit.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/listbox.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>plugins/forms/multiselect.js"></script>
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
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>bootstrap.min.js"></script>
        <script type="text/javascript" src="<?php echo $_layoutParam['route_js']?>application.js"></script>
        <?php
    
           if(isset($_layoutParam['js']) && count($_layoutParam['js'])){
               for($i=0; $i<count((array)$_layoutParam['route_js']); $i++){
        ?> 
        <script type="text/javascript" src="<?php echo $_layoutParam['js'][$i];?>"></script>
        
        <?php           
               }
           } 
        ?>
    </div>
    <!-- /Pie de página -->
</body>

</html>