	<td id="cellBody">
		<h3>Recuperación de Clave. Te enviamos un mail.</h3>
		<div id="contentLogin">
			<?php if (isset($error_login) && $error_login): ?>
				<div class=""> Mail no encontrado </div>
			<?php endif; ?>
			<form action="<?php echo $form_validate; ?>" method="post" name="sidebarLogin" target="_self" id="sidebarLoginForm">
				<label>Usuario<br />
					<input type="text" name="username" />
				</label>
				<br />
				<input name="btLogin" type="submit" class="btn" id="btLogin" value="Ingresar" />
			</form>
			<p></p>
		</div>
		<p align="center">
			<a href="<?=site_url('login/crear_cuenta');?>">Crea tu propia cuenta</a> para publicar tus artículos y acceder a todos los contenidos
		</p>
	</td>


