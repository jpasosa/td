


<td id="cellBody">
	<h3>Administrar Usuario </h3>
	<form action="" method="post" name="contentPerfil" target="_self" id="contentPerfilForm" enctype="multipart/form-data">
		<?php if (isset($errors)): ?>
			<div class="errors">
				<?php echo $errors; ?>
			</div>
		<?php endif; ?>
		<div id="contentPerfil">
			<p>
				Los datos identificados con (*) serán mostrados públicamente, los identificados con (**) serán privados y utilizados
				para contactarte en caso de que realices alguna transacción en el sitio. <br/> Todos los datos son necesarios.
			</p>
			<div class="floatleft">
				<input type="hidden" name="idUsuarios" value="<?php echo $user['idUsuarios']; ?>" />
				<input type="hidden" name="idRoles" value="<?php echo $user['idRoles']; ?>" />
				<label>Nombre (*)<br />
					<input type="text" name="nombre" value="<?php echo $user['nombre']; ?>" />
				</label>
				<br />
				<label>Apellido (*)<br />
					<input type="text" name="apellido" value="<?php echo $user['apellido']; ?>" />
				</label>
				<br />
				<label>Nombre a Mostrar (*)<br />
					<input type="text" name="nombre_mostrar" value="<?php echo $user['nombre_mostrar']; ?>" />
				</label>
				<br />
				<label>Dirección de correo (**)<br />
					<input type="text" name="email" value="<?php echo $user['email']; ?>" />
				</label>
				<br />
				<label>Intereses (*)<br />
					<input type="text" name="intereses" value="<?php echo $user['intereses']; ?>" />
				</label>
				<br />
				<label>Reseña biográfica (*)<br />
					<textarea name="biografia" class="biografia"><?php echo $user['biografia']; ?></textarea>
				</label>
				<br />
				<label>Profesión (*)<br />
					<input type="text" name="profesion" value="<?php echo $user['profesion']; ?>" />
				</label>
				<br />
				<label>Fecha de nacimiento (**)<br />
					<?php if ($user['fecha'] == '' || $user['fecha'] == '0000-00-00'): ?>
						<input type="text" id="fecha" name="fecha" value="" />
					<?php else: ?>
						<input type="text" id="fecha" name="fecha" value="<?php echo $user['fecha']; ?>" />
					<?php endif ?>
				</label>
				<br />
				<label>País de residencia (*)<br />
					<input type="text" name="lugar" value="<?php echo $user['lugar']; ?>" />
				</label>
				<br />
				<label>Teléfono (**)<br />
					<input type="text" name="telefono" value="<?php echo $user['telefono']; ?>" />
				</label>
			</div>
			<div class="floatright">
				<br />
				<label class="avatar">Avatar (*)<br />
					<input type="file" name="avatar" />
					<?php if(isset($user['avatar']) && !empty($user['avatar'])):?>
						<input type="hidden" name="name_avatar"
								value="<?php if (isset($user['avatar'])): echo $user['avatar']; endif; ?>" />
						<img class="avatar" src="<?php echo RAIZ . '' . UPLOADS.  "usuarios/avatar/" . $user['avatar'];?>" width="50" height="50">
						<a style="font-size: 9px;" href="<?php echo RAIZ . UPLOADS.  "usuarios/avatar/" . $user['avatar'];?>" target="_blank">
							<?php if ( isset($user['avatar_para_mostrar']) && $user['avatar_para_mostrar'] != '') : ?>
								<?php echo $user['avatar_para_mostrar'];?>
							<?php else: ?>
								<?php echo $user['avatar'];?>
							<?php endif; ?>
						</a>
                            		<input class="del_avatar" type="checkbox" name="del_avatar" value="<?php echo $user['del_avatar'];?>" />
                            		Eliminar avatar
					<?php endif;?>
				</label>
				<br />
				<label class="avatar">DATOS DE ENTREGA (*)<br />
				Importante: No olvides colocar tu dirección real, es donde recibirás tus pagos!
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

				<!-- <input name="btSeleccionar" id="btSeleccionar" type="submit" value="Selecc." class="btn"/> -->
				<br/>
				<!--
				<p>
					Autor (Si/No) <br />
					<select name="esAutor">
						<option value="1" <?php if($user['esAutor'] == 1) echo 'selected="selected"';?>>Si</option>
						<option  value="0" <?php if($user['esAutor'] == 0) echo 'selected="selected"';?>>No</option>
					</select>
				</p> -->
				<!-- <p>
					Editorial (Si/No) <br />
					<select name="esEditorial">
						<option value="1" <?php if($user['esEditorial'] == 1) echo 'selected="selected"';?>>Si</option>
						<option  value="0" <?php if($user['esEditorial'] == 0) echo 'selected="selected"';?>>No</option>
					</select>
				</p> -->

				<!-- <p>
					Estado (Activado/Bloqueado/Observado/Destacado) <br />
					<select name="estado">
						<?php foreach ($estados AS $est): ?>
								<option value="<?php echo $est['idEstados']; ?>"
									<?php if ($user['estado'] == $est['idEstados']): echo 'selected="selected"'; ?><?php endif; ?>>
									<?php echo $est['estado']; ?>
								</option>
						<?php endforeach ?>
					</select>
				</p> -->

				<p>
					Observaciones <br />
					<textarea name="observaciones" class="observaciones"><?php echo $user['observaciones']; ?></textarea>
				</p>
				<!-- <p xclass="btn btnmedio" >
					<a href="#">Cargar Regalías</a>
				</p> -->
				<!-- <input name="btEliminar" id="btEliminar" type="submit" value="Eliminar" class="btn" /> -->
				<p>&nbsp; </p>
			</div>
		</div>
		<p>
			<input name="btGuardar" id="btGuardar" type="submit" value="Guardar" class="btn"/>
		</p>
	</form>
</td>
