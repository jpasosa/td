<td rowspan="2" id="cellSide">
	<table width="100%" border="0" cellpadding="0" cellspacing="0" id="sidebar">
		<tr>
			<td>
				<div align="center">
					<img src="<?php echo ASSETS . 'style/images/logorojo.png'; ?>" alt="WordRev" width="128" height="31" />
				</div>
			</td>
		</tr>
		<?php //if ($section == 'front_login.index' || $section == 'front_login.validate' || $login_user == true): ?>
		<?php if (!$login_user): ?>
			<tr>
				<td>
					<div id="sidebarLogin">
						<p> ¿Ya eres miembro?</p>
						<form action="<?php echo $form_validate; ?>" method="post" name="sidebarLogin" target="_self" id="sidebarLoginForm">
							<label>Usuario<br />
								<input type="text" name="username" />
							</label>
							<br />
							<label>Clave<br />
								<input type="password" name="password" />
							</label>
							<br />
							<input name="btLogin" type="submit" class="btn" id="btLogin" value="Ingresar" />
							<label></label>
						</form>
						<p> <a href="<?=site_url('login/crear_cuenta');?>">Crea tu propia cuenta!</a></p>
					</div>
				</td>
			</tr>
		<?php else: ?>
			<tr>
				<td>
					<div id="sidebarLogin">
						<p class="tituloPanel">Tu Cuenta </p>
						<p> <a href="<?=site_url('tucuenta/editarPerfil/' . $id_user);?>">Editar perfil</a></p>
						<p> <a href="<?=site_url('tucuenta/mis_favoritos/' . $id_user . '/pagina/1');?>">Favoritos</a></p>
						<p> <a href="<?=site_url('tucuenta/mis_publicaciones/' . $id_user . '/pagina/1');?>">Publicaciones</a></p>
						<p> <a href="<?=site_url('trabajos/alta');?>">Nueva publicación</a></p>
						<p> <a href="<?=site_url('tucuenta/mis_estadosdecuenta/' . $id_user . '/pagina/1');?>">Estado de cuenta</a></p>
						<p> <a href="<?=site_url('tucuenta/acceso_editorial');?>">Acceso para Editoriales</a></p>
					</div>
				</td>
			</tr>
		<?php endif; ?>
		<tr>
			<td>
				<div class="boxSideBarTitle">Ultimos Artículos</div>
				<div class="boxSideBar" id="boxUltimosArticulos">
					<?php foreach ($last_works AS $lw): ?>
						<div>
							<a href="<?=site_url('trabajos/ver/' . $lw['idTrabajos']);?>"><?php echo $lw['titulo']; ?></a>
						</div>
					<?php endforeach ?>
				</div>
			</td>
		</tr>
		<tr>
			<td>
				<div class="boxSideBarTitle" >Nuevos Autores</div>
				<div class="boxSideBar" id="boxNuevosAutores">
					<div>
						<?php foreach ($autores AS $aut): ?>
							<a href="<?=site_url('tucuenta/perfil/' . $aut['idUsuarios']);?>">
								<?php echo $aut['nombre_mostrar']; ?>
							</a>
						<?php endforeach ?>
					</div>
				</div>
			</td>
		</tr>
	</table>
</td>

