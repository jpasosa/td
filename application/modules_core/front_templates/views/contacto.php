
<td id="cellBody">
	<h3>Contacto</h3>
	<blockquote>
		<p style="font-size: 12px;">Recibimos tus consultas, comentarios o sugerencias a trav&eacute;s del siguiente formulario. </p>
		<p style="font-size: 12px;">No olvides revisar nuestra secci&oacute;n de <strong><a href="preguntas_frecuentes.html">preguntas frecuentes</a></strong> para encontrar respuesta a las inquietudes m&aacute;s comunes. </p>
	</blockquote>
	<?php if (isset($errors)): ?>
		<?php foreach ($errors AS $err): ?>
			<div class="errors">
				<?php echo $err; ?>
			</div>
		<?php endforeach; ?>
	<?php endif; ?>
	<div id="contentContacto">
		<form action="<?php echo $form_validate; ?>" method="post" name="contacto" target="_self" id="contactoForm">
			<label>Nombre y Apellido <br />
				<input type="text" name="nombre" value="<?php if (isset($contacto['nombre'])): echo $contacto['nombre']; endif; ?>" />
			</label>
			<br />
			<label>Direcci&oacute;n de correo<br />
				<input type="text" name="email" value="<?php if (isset($contacto['email'])): echo $contacto['email']; endif; ?>" />
			</label>
			<br />
			<label>Consulta o Comentario <br />
				<textarea name="comentario"><?php if (isset($contacto['comentario'])): echo $contacto['comentario']; endif; ?></textarea>
				<br />
				<br />
			</label>
			<input name="EnviarContacto" class="btn" type="submit" id="EnviarContacto" value="Enviar" />
			<br />
		</form>
	</div>
	<p align="center">
		<a href="<?=site_url('login');?>">Si tienes cuenta ingresa aquí</a>
		&nbsp;||&nbsp;
		<a href="<?=site_url('login/recuperar_clave');?>">&iquest;Olvidaste tu clave? Click aquí</a>
	</p>
</td>
