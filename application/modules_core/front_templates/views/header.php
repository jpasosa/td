<body id="<?php echo $body_id; ?>">
	<table width="90%" border="0" cellpadding="0" cellspacing="0" id="maintable">
		<tr>
			<td colspan="2" id="cellTop">
				<table width="100%" border="0" cellpadding="0" cellspacing="0" id="header">
					<tr>
						<td class="headerleft">&nbsp;</td>
						<td class="headercenter1">
							<div class="menuheader1">
								<ul id="menuheader1">
									<li id="nav-home"><a href="<?=site_url('homepage');?>">Inicio</a></li>
									<li id="nav-temas"><a href="<?=site_url('temas/listar');?>">Temas</a></li>
									<li id="nav-destacados"><a href="<?=site_url('destacados/listar');?>">Destacados</a></li>
									<li id="nav-beneficios"><a href="<?=site_url('beneficios');?>">Beneficios</a></li>
								</ul>
							</div>
						</td>
						<td class="headercenter2">
							<div class="menuheader2">

								<form method="post" enctype="multipart/form-data" action="<?php echo $form_action_search;?>">
									<?php if ($login_user): ?>
										<a href="<?=site_url('front_login/logout');?>">Salir</a>
										<a href="<?=site_url('trabajos/alta');?>">Publicar</a>
									<?php else: ?>
										<a href="<?=site_url('login');?>">Ingresar</a>
									<?php endif; ?>
									<?php if (!$login_user): ?>
										<a href="<?=site_url('login/crear_cuenta');?>">Unirte</a>
									<?php endif; ?>
									<label>
										&nbsp;&nbsp;Buscar: <input name="buscar" type="text" id="searchheader" size="30" maxlength="50" />
									</label>
								</form>
							</div>
						</td>
						<td class="headerright">&nbsp;</td>
					</tr>
				</table>&nbsp;
			</td>
		</tr>
