<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php if(checkRol('administrador', $this->session)):?>
       <body >
<?php else:?>
    <body class="sidebar_hidden">
<?php endif;?>

<div id="loading_layer" style="display:none"><img src="<?php echo ADMIN_FOLDER;?>img/ajax_loader.gif" alt="" /></div>

<div id="maincontainer" class="clearfix" >

<!-- header -->
<i class="icon-search"></i>
<header>
    <div class="navbar navbar-fixed-top">
        <div class="navbar-inner">
            <div class="container-fluid">
                <a class="brand" href="<?php echo site_url('admin/usuarios/listar');?>">
                    <img src="<?php echo ASSETS . 'style/images/logoblanco.png'; ?>">&nbsp;&nbsp;&nbsp;
                    <i class="icon-home icon-white"></i> Inicio
                </a>
                <ul class="nav user_menu pull-right">
                    <li class="divider-vertical hidden-phone hidden-tablet"></li>
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                            <img src="<?php echo ADMIN_FOLDER;?>img/user_avatar.png" alt="" class="user_avatar" />Perfil<b class="caret"></b>
                        </a>
                        <ul class="dropdown-menu">
                            <li><a href="<?php echo site_url('admin/usuarios/perfil');?>">Mis datos</a></li>
                            <li class="divider"></li>
                            <li><a href="<?php echo site_url('admin/login/close');?>">Cerrar sesión</a></li>
                        </ul>
                    </li>
                </ul>
                <a data-target="nav-collapse" data-toggle="collapse" class="btn_menu">
                    <span class="icon-align-justify icon-white"></span>
                </a>
                <nav>
                    <div class="nav-collapse">
                        <ul class="nav">
                            <?php if(checkRol('administrador', $this->session)):?>
                                <li class="dropdown">
                                    <?php if($this->session->userdata('foto') == ''):?>
                                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                            <i class="icon-user icon-white"></i> Usuarios <b class="caret"></b>
                                        </a>
                                    <?php else:?>
                                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                            <!-- <img src="<?php echo PUBLIC_FOLDER;?>assets/Usuarios/<?php echo $this->session->userdata('foto');?>" /> -->
                                            Usuarios <b class="caret"></b>
                                        </a>
                                    <?php endif;?>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo site_url('admin/usuarios/alta');?>">Alta</a></li>
                                        <li><a href="<?php echo site_url('admin/usuarios/listar');?>">Listar</a></li>
                                    </ul>
                                </li>
                            <?php else:?>
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown"><i class="icon-folder-open icon-white"></i> Trabajos<b class="caret"></b> </a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo site_url('admin/trabajos/nuevo');?>">Nuevo</a>
                                        </li>
                                        <li class="divider"></li>
                                        <li><a href="<?php echo site_url('admin/trabajos/mis_trabajos');?>">Mis trabajos</a></li>
                                    </ul>
                                </li>
                            <?php endif;?>
                        </ul>
                        <ul class="nav">
                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="icon-user icon-white"></i> Trabajos <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo site_url('admin/trabajos/nuevo');?>">Nuevo</a></li>
                                    <li><a href="<?php echo site_url('admin/trabajos/listar');?>">Listar todos</a></li>
                                    <li><a href="<?php echo site_url('admin/trabajos/pendientes');?>">Listar pendientes</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav">
                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="icon-user icon-white"></i> Categorias <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo site_url('admin/categorias/nueva');?>">Nueva categoría</a></li>
                                    <li><a href="<?php echo site_url('admin/categorias/listar');?>">Listar</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav">
                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="icon-user icon-white"></i> Pedidos y Compras <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li><a href="<?php echo site_url('admin/compras/listar_pedidos');?>">Listar Pedidos </a></li>
                                    <li><a href="<?php echo site_url('admin/compras/listar_compras');?>">Listar Compras</a></li>
                                    <li><a href="<?php echo site_url('admin/compras/cargar_compra');?>">Cargar Compra</a></li>
                                    <li><a href="<?php echo site_url('admin/compras/aviso_regalias');?>">Alertas Regalías</a></li>
                                </ul>
                            </li>
                        </ul>
                        <ul class="nav">
                            <li class="dropdown">
                                <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                                    <i class="icon-user icon-white"></i> Pagos <b class="caret"></b>
                                </a>
                                <ul class="dropdown-menu">
                                    <li>
                                        <a href="<?php echo site_url('admin/pagos/listado_autores');?>">Listado Autores </a>
                                    </li>
                                    <li><a href="<?php echo site_url('admin/pagos/listado_pagos_realizados');?>">Listado Pagos Realizados</a></li>
                                </ul>
                            </li>
                        </ul>
                    </div>

                </nav>
            </div>
        </div>
    </div>
</header>
<!-- main content -->
<div id="contentwrapper">
    <div class="main_content container" style="min-height:400px;">
