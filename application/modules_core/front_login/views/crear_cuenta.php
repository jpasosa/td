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
			<label class="avatar">DATOS DE ENTREGA (*)<br />
			</label>
			<br />
			<label class="avatar">Dirección (calle)<br />
				<input type="text" name="direccion_calle" value="<?php echo $user['direccion_calle']; ?>" />
			</label>
			<br />
			<label class="avatar">Dirección (número)<br />
				<input type="text" name="direccion_numero" value="<?php echo $user['direccion_numero']; ?>" />
			</label>
			<br />
			<label class="avatar">Cod. Postal<br />
				<input type="text" name="cod_postal" value="<?php echo $user['cod_postal']; ?>" />
			</label>
			<br />
			<label class="avatar">Localidad<br />
				<input type="text" name="localidad" value="<?php echo $user['localidad']; ?>" />
			</label>
			<br />
			<label class="avatar">Ciudad<br />
				<input type="text" name="ciudad" value="<?php echo $user['ciudad']; ?>" />
			</label>
			<br />
			<label class="avatar">País<br />
				<input type="text" name="pais" value="<?php echo $user['pais']; ?>" />
			</label>
			<br />


			<label class="avatar">Forma de Pago<br />
			<select name="idFormasPago" id="idFormasPago" >
				<?php foreach($formas_pago AS $pago):?>
					<option value="<?php echo $pago['idFormasPago'];?>" <?php if($pago['idFormasPago'] == $user['idFormasPago']) echo 'selected="selected"'; ?> >
						<?php echo $pago['nombre'];?>
					</option>
				<?php endforeach;?>
			</select>


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
