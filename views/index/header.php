<!DOCTYPE html>
<html lang="es">
<head>
	<link id="page_favicon" href="<?php echo $_layoutParam['route_img']?>logos/favicon.ico" rel="icon" type="image/x-icon" />
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1, maximum-scale=1">
	<title><?php  echo $this->titulo . " | "; echo $_layoutParam['titulo_principal'] ;?></title>
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
