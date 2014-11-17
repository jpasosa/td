<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es" content='text/html; charset=utf-8'>


<!-- <!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd"> -->
<!-- <html xmlns="http://www.w3.org/1999/xhtml"> -->

<head>
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title><?php echo $title ?></title>

	<link rel="icon" href="<?php echo ASSETS . 'front/templates/images/favicon.ico'; ?>  " type="image/vnd.microsoft.icon" />
	<link rel="shortcut icon" href="favicon.ico" />

	<script src="http://code.jquery.com/jquery-1.9.1.js"></script>
	<script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

	<!-- Latest compiled and minified CSS -->    <!-- el estilo del bootstrap me esta rompiendo el css del sitio -->
	<link rel="stylesheet" href=" <?php echo ASSETS . 'bootstrap/dist/css/bootstrap.css' ?>">

	<!-- Latest compiled and minified JavaScript -->
	<script src="<?php echo ASSETS . 'bootstrap/dist/js/bootstrap.min.js' ?>"></script>


	<link href=" <?php echo ASSETS . 'front/templates/css/wordrev.css'; ?>" rel="stylesheet" type="text/css" media="screen" />



	<?php if ($section == 'front_works.add'
				|| $section == 'front_login.crear_cuenta'
				|| $section == 'front_login.validate_crearcuenta'
				|| $section == 'front_works.edit'
				|| $section == 'front_templates.preguntas_frecuentes'): ?>
		<link href=" <?php echo ASSETS . 'css/jquery-ui.css'; ?>" rel="stylesheet" type="text/css" /><!-- para del datepicker -->
		<script src=" <?php echo ASSETS . 'categorys/get.subcategorys.js'; ?> "></script>
		<!-- DATEPICKER EN ESPAÑOL -->
		<script src=" <?php echo ASSETS . 'js/datepicker.spanish.js'; ?> ">
		<script src=" <?php echo ASSETS . 'categorys/get.subcategorys.js'; ?> "></script>

	<?php endif; ?>

	<!-- CARGO DATEPICKER PARA EDITAR PERFIL -->
	<?php if ($section == 'front_tucuenta.editarPerfil'): ?>
		<link href=" <?php echo ASSETS . 'css/jquery-ui.css'; ?>" rel="stylesheet" type="text/css" /><!-- para del datepicker -->
		<script src=" <?php echo ASSETS . 'js/datepicker.spanish.js'; ?> ">
		<link href=" <?php echo ASSETS . 'css/admin_panel.css'; ?>" rel="stylesheet" type="text/css" /><!-- EL PANEL SUPERIOR DE ADMINISTRACION -->
	<?php endif ?>

	<!-- CARGO ESTILO DEL PANEL SUPERIOR DE ADMINISTRACION -->
	<?php if ($section == 'front_tucuenta.editarPerfil' || $section == 'front_works.add' || $section == 'front_works.edit'): ?>
		<link href=" <?php echo ASSETS . 'style/admin_panel.css'; ?>" rel="stylesheet" type="text/css" /><!-- EL PANEL SUPERIOR DE ADMINISTRACION -->
	<?php endif ?>


	<!-- ELIMINACION DE LA PUBLICACION EN EL FRONTEND -->
	<?php if ($section == 'front_tucuenta.mis_publicaciones'): ?>
		<!-- <script src=" <?php echo ASSETS . 'publicaciones/del_publicacion.js'; ?> "></script> -->
	<?php endif ?>

	<!-- AGREGAR A FAVORITOS -->
	<?php if ($section == 'front_works.show'): ?>
		<script src=" <?php echo ASSETS . 'works/add_favoritos.js'; ?> "></script>
	<?php endif ?>

	<!-- SACAR DE FAVORITOS -->
	<?php if ($section == 'front_tucuenta.mis_favoritos'): ?>
		<script src=" <?php echo ASSETS . 'works/del_favoritos.js'; ?> "></script>
	<?php endif ?>

	<script type="text/javascript">
		// ahora se puede usar RUTA desde los .js, cambia si es LOCAL o DEMO
		var RUTA = "<?php echo RUTA; ?>";
	</script>










</head>