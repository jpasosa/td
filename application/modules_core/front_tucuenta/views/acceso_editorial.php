
<td id="cellBody">
	<h3>Acceso especial para Editoriales </h3>
	<form action="<?php echo $form_action; ?>" method="post" name="contentPermisos" target="_self" id="contentPermisosForm">
		<?php if (isset($errors)): ?>
			<?php foreach ($errors AS $message): ?>
				<?php echo $message . '<br />'; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<div id="contentPermisos">
			<p>WordRev brinda un acceso especial para editoriales que permite ver mayores detalles de los artículos publicados.</p>
			<p>Si Ud. pertenece a una por favor envíe el siguiente pedido para que las funciones extras sean activadas en su cuenta.</p>

			<div class="floatleft">
				<label>Nombre de la Editorial (*)<br />
					<input type="text" name="nombre_editorial" value="<?php echo $nombre_editorial; ?>" />
				</label>
				<br />
				<label>Sitio web de la Editorial (*)<br />
					<input type="text" name="sitio_editorial" value="<?php echo $web; ?>" />
				</label>
				<br />
				<label>Solicito acceso editorial  (*)<br />
					<input type="checkbox" name="solicitar" checked="checked" />
				</label>
				<p>
					<input name="enviar_solicitud" id="btPrivilegios" type="submit" value="Enviar" class="btn" />
				</p>
			</div>
			<div class="floatright">
				<br/ >
				<?php if ($author['avatar'] == ''): ?>
					<img src="<?php echo ASSETS . 'style/images/default_avatar.png'; ?>" alt="Foto del Autor" width="285" height="166" />
				<?php else: ?>
					<img src=" <?php echo UPLOADS . 'usuarios/avatar/' . $author['avatar']; ?>"  width="285" height="166" />
				<?php endif; ?>
				<!-- <img src="style/images/fotoperfil.png" /> -->
			</div>
		</div>
	</form>

</td>

