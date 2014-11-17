<td id="cellBody">

	<h3>Crea tu cuenta! </h3>
	<!-- <blockquote> -->
		<p>
			Crear tu cuenta es gratis y muy sencillo, sólo es necesario completar el siguiente formulario
			y activar tu cuenta a través del mensaje de verificación que enviaremos a tu casilla de correo.
		</p>
	<!-- </blockquote> -->
	<div id="contentRegistro">
		<?php if ($error_validate): ?>
			<div style="color: red;">
				<?php echo $error_campos; ?>
			</div>
		<?php endif ?>
		<form action="<?php echo $form_validate_cuenta; ?>" method="post" enctype="multipart/form-data" name="contentRegistro" target="_self" id="contentRegistroForm">
			<label>Nombre (*)<br />
				<input type="text" name="user" value="<?php echo $usuario_nuevo['user']; ?>" />
			</label>
			<label>Apellido<br />
				<input type="text" name="apellido" value="<?php echo $usuario_nuevo['apellido']; ?>" />
			</label>
			<br />
			<label>Dirección de correo (*)<br />
				<input type="text" name="email" value="<?php echo $usuario_nuevo['email']; ?>" />
			</label>
			<br />
			<label>Clave (*)<br />
				<input type="password" name="pass"  value="<?php echo $usuario_nuevo['pass']; ?>" />
			</label>
			<br />
			<label>Repite la clave (*)<br />
				<input type="password" name="repeat_pass" value="<?php echo $usuario_nuevo['repeat_pass']; ?>" />
			</label>
			<label>Telefono<br />
				<input type="text" name="telefono" value="<?php echo $usuario_nuevo['telefono']; ?>" />
			</label>
			<label>Avatar<br />
				<input type="file" name="avatar" value="<?php echo $usuario_nuevo['avatar']; ?>" />
				<input type="hidden" name="avatar_name" />
			</label>
			<label>Fecha de Nacimiento<br />
				<input id="fecha" type="text" name="fecha" value="<?php echo $usuario_nuevo['fecha']; ?>" />
			</label>
			<label>Intereses<br />
				<input type="text" name="intereses" value="<?php echo $usuario_nuevo['intereses']; ?>" />
			</label>
			<label>País<br />
				<input type="text" name="lugar" value="<?php echo $usuario_nuevo['lugar']; ?>" />
			</label>
			<label>Profesion<br />
				<input type="text" name="profesion" value="<?php echo $usuario_nuevo['profesion']; ?>" />
			</label>
			<!-- <label>Biografia<br />
				<input type="text" name="biografia" />
			</label> -->
			<p>
			<input name="btRegistro" id="btRegistro" type="submit" value="Crear cuenta!" class="btn"/>
			</p>
		</form>
	</div>
	<p align="center">
		<!--
		<a href="login.php">Si tienes cuenta ingresa aquí</a>
		&nbsp;||&nbsp;
		<a href="recuperar.php">&iquest;Olvidaste tu clave? Click aquí</a>
		-->
	</p>
</td>
