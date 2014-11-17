<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>
<!DOCTYPE html>
<html lang="en" class="login_page">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0" />
        <title>
            <?php if (isset($title) && !empty($title)): ?>
                        <?php echo $title; ?>
            <?php else: ?>
                        <?php 'Ingreso'; ?>
            <?php endif ?>
        </title>

        <!-- Bootstrap framework -->
            <link rel="stylesheet" href=" <?php echo ASSETS . 'bootstrap_old/css/bootstrap.min.css'; ?>" />
            <link rel="stylesheet" href=" <?php echo ASSETS . 'bootstrap_old/css/bootstrap-responsive.min.css'; ?>" />

        <!-- theme color-->
            <link rel="stylesheet" href=" <?php echo ASSETS . 'admin/css/blue.css';?>" />
        <!-- tooltip -->
            <link rel="stylesheet" href=" <?php echo ASSETS . 'admin/lib/qtip2/jquery.qtip.min.css';?>" />
        <!-- main styles -->
            <link rel="stylesheet" href=" <?php echo ADMIN_FOLDER;?>css/style.css" />

        <!-- Favicon -->
            <link rel="shortcut icon" href="<?php echo ADMIN_FOLDER;?>lib/datatables/images/favicon.ico" />

        <link href='http://fonts.googleapis.com/css?family=PT+Sans' rel='stylesheet' type='text/css'>

        <!--[if lte IE 8]>
            <script src="<?php echo ADMIN_FOLDER;?>js/ie/html5.js"></script>
            <script src="<?php echo ADMIN_FOLDER;?>js/ie/respond.min.js"></script>
        <![endif]-->
    </head>


