<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="es">
    <head>
        <meta charset="utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title><?php if(isset($title) and !empty($title)) echo $title; else echo "."; ?></title>

        <!-- <script src="<?php echo ADMIN_FOLDER;?>js/jquery.min.js"></script> -->

        <script src="http://code.jquery.com/jquery-1.9.1.js"></script>
        <script src="http://code.jquery.com/ui/1.10.1/jquery-ui.js"></script>

        <!-- Bootstrap framework -->
            <link rel="stylesheet" href="<?php echo ASSETS . 'bootstrap_old/css/bootstrap.min.css'; ?>" />
            <link rel="stylesheet" href="<?php echo ASSETS . 'bootstrap_old/css/bootstrap-responsive.min.css'; ?>" />

        <!-- gebo blue theme-->
            <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>css/blue.css" id="link_theme" />

        <!-- breadcrumbs-->
            <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>lib/jBreadcrumbs/css/BreadCrumb.css" />

        <!-- tooltips-->
            <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>lib/qtip2/jquery.qtip.min.css" />

        <!-- colorbox -->
            <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>lib/colorbox/colorbox.css" />

        <!-- code prettify -->
            <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>lib/google-code-prettify/prettify.css" />

        <!-- notifications -->
            <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>lib/sticky/sticky.css" />

        <!-- splashy icons -->
            <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>img/splashy/splashy.css" />

        <!-- flags -->
            <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>img/flags/flags.css" />

        <!-- calendar -->
            <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>lib/fullcalendar/fullcalendar_gebo.css" />

        <!-- Choosen  -->
        <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>lib/chosen/chosen.css" />

         <!-- nice form elements -->
         <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>lib/uniform/Aristo/uniform.aristo.css" />


        <!-- Multiselect -->
        <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>lib/multiselect/css/multi-select.css" />

        <!-- main styles -->
            <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>css/style.css" />

            <link rel="stylesheet" href="http://fonts.googleapis.com/css?family=PT+Sans" />

        <!-- Favicon -->
            <link rel="shortcut icon" href="<?php echo ADMIN_FOLDER;?>/favicon.ico" />


        <!--[if lte IE 8]>
            <link rel="stylesheet" href="<?php echo ADMIN_FOLDER;?>/css/ie.css" />
            <script src="<?php echo ADMIN_FOLDER;?>/js/ie/html5.js"></script>
            <script src="<?php echo ADMIN_FOLDER;?>/js/ie/respond.min.js"></script>
            <script src="<?php echo ADMIN_FOLDER;?>/lib/flot/excanvas.min.js"></script>
        <![endif]-->


        <!-- <script src="<?php echo ADMIN_FOLDER;?>/js/jquery.min.js"></script> -->
        <!-- <script src="<?php echo ADMIN_FOLDER;?>/lib/jquery-ui/jquery-ui-1.8.20.custom.min.js"></script> -->



        <script>
            //* hide all elements & show preloader
            document.documentElement.className += 'js';
        </script>


        <script type="text/javascript">var _public_folder = '<?php echo PUBLIC_FOLDER_ADMIN;?>';</script>
        <!--<script type="text/javascript" src="<?php //echo PUBLIC_FOLDER;?>assets/js/funciones.js"></script> -->


        <?php if(isset($script)):?>
            <?php echo $script;?>
        <?php endif;?>

        <script type="text/javascript">
            // ahora se puede usar RUTA desde los .js, cambia si es LOCAL o DEMO
            var RUTA = "<?php echo RUTA; ?>";
        </script>
        <!-- AJAX CON EL AUTOR EN CARGAR COMPRA MANUALMENTE -->
        <?php if (isset($section) && $section == 'admin_compras.cargar_compra'): ?>
            <script src=" <?php echo ASSETS . 'compras/ajax_author_works.js'; ?> "></script>
        <?php endif; ?>

        <style>
        .navbar-inner,.navbar-fixed-top,.navbar {background-color:black;}
        .navbar-inner .container-fluid {background-color:black;}
        </style>

    </head>
