<body id="crearcuenta">
        <table width="90%" border="0" cellpadding="0" cellspacing="0" id="maintable">
                <tr>
                        <td colspan="2" id="cellTop">
                                <table width="100%" border="0" cellpadding="0" cellspacing="0" id="headerAdmin">
                                        <tr>
                                                <td class="headercenterAdmin1">
	                                                <div class="menuheaderAdmin1">
		                                                <!-- <ul id="menuheaderAdmin1"> //-->
		                                                <p>
			                                                <!-- <a href="admin_panel.php">Panel</a> |  //-->
			                                                &nbsp; MENU &nbsp; &nbsp; : (
			                                                <a href="#">Inicio y Alertas</a> |
			                                                <a href="#">Rankings</a> |
			                                                <a href="#">Usr add</a>
			                                                <a href="#">usr mod</a> |
			                                                <a href="#">Artic add</a>
			                                                <a href="#">Artic mod</a> |
			                                                <a href="#">Cargar venta</a> |
			                                                <a href="#">Buscar</a>
			                                                <br />
			                                                &nbsp; Acciones: (
			                                                <a href="#">Pub pend</a> -
			                                                <a href="#">User pend</a> -
			                                                <a href="#">Pagos pend</a> -
			                                                <a href="#">Ultimas Pub</a> -
			                                                <a href="#">Ultimos Usr</a> -
			                                                <a href="#">Temas y Sub T</a> -
			                                                <a href="#">Configuraci&oacute;n</a>
			                                                )
		                                                </p>
		                                                <!-- </ul> //-->
	                                                </div>
                                                </td>
                                                <td class="headercenterAdmin2">
	                                                <div class="menuheader2">
		                                                <?php if ($login_user): ?>
		                                                        <a href="<?=site_url('front_login/logout');?>">Salir</a>
		                                                <?php else: ?>
		                                                        <a href="<?php echo '/login'; ?>">Ingresar</a>
		                                                <?php endif; ?>
		                                                <a href="#">Unirte</a>
		                                                Buscar
		                                                <label>
		                                                	<input name="searchheader" type="text" id="searchheader" size="30" maxlength="50" />
		                                                </label>
	                                                </div>
								</td>
                                        </tr>
                                </table>
                        </td>
                </tr>

<!--
<body id="crearcuenta">
        <table width="90%" border="0" cellpadding="0" cellspacing="0" id="maintable">
                <tr>
                        <td colspan="2" id="cellTop">
					<div id='cssmenu'>
						<ul>
							<li class='active'>
								<a href='index.html'>
									<span>Inicio y Alertas</span>
								</a>
							</li>
							<li>
								<a href='#'>
									<span>Rankings</span>
								</a>
							</li>
							<li class='has-sub'>
								<a href='#'>
									<span>Usuarios</span>
								</a>
								<ul>
									<li>
										<a href='#'>
											<span>Crear</span>
										</a>
									</li>
									<li class='last'>
										<a href='#'>
											<span>Editar</span>
										</a>
									</li>
								</ul>
							</li>
							<li class='has-sub'><a href='#'><span>Artículos</span></a>
								<ul>
									<li>
										<a href='#'><span>Sub Item</span></a>
									</li>
									<li class='last'>
										<a href='#'><span>Editar</span></a>
									</li>
								</ul>
							</li>
							<li>
								<a href='#'>
									<span>Cargar Venta</span>
								</a>
							</li>
							<li class='has-sub'>
								<a href='#'>
									<span>Tareas pendientes</span>
								</a>
								<ul>
									<li>
										<a href='#'><span>Publicaciones pendientes</span></a>
									</li>
									<li>
										<a href='#'><span>Usuarios pendientes</span></a>
									</li>
									<li class='last'>
										<a href='#'><span>Pagos pendientes</span></a>
									</li>
								</ul>
							</li>
							<li class='has-sub'>
								<a href='#'><span>Administración</span></a>
								<ul>
									<li>
										<a href='#'><span>Publicaciones recientes</span></a>
									</li>
									<li>
										<a href='#'><span>Usuarios recientes</span></a>
									</li>
									<li>
										<a href='#'><span>Temas y Subtemas</span></a>
									</li>
									<li class='last'>
										<a href='#'><span>Configuración</span></a>
									</li>
								</ul>
							</li>
							<li class='last'><a href='#'><span>Buscar</span></a></li>
						</ul>
					</div>
					<div style="clear:both; margin: 0 0 30px 0;">&nbsp;</div>

                        </td>
                </tr>
-->















