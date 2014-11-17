<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');?>
<div class="row-fluid">
	<div class="span12">
		<h3 class="heading"><a href="<?php echo PUBLIC_FOLDER_ADMIN;?>trabajos/listar.html">Trabajos</a></h3>
		<div class="row-fluid">
			<div class="span8">
				<?php if(isset($errors)):?> <!-- ERRORES DE VALIDACIÓN -->
				<div class="alert alert-error">
					<a class="close" data-dismiss="alert" href="#">&times;</a>
					<?php echo $errors;?>
				</div>
				<?php endif;?>
				<?php if(isset($success)):?> <!-- EN EL UPDATE, AVISA QUE MODIFICÓ CON ÉXITO -->
					<div class="alert alert-success">
						<a class="close" data-dismiss="alert" href="#">&times;</a>
						<?php echo $success;?>
					</div>
				<?php endif;?>
				<form method="post" class="form-horizontal" enctype="multipart/form-data" action="<?php echo $form_action;?>">
					<fielset>
						<!-- USUARIOS -->
						<div class="control-group">
							<?php if(checkRol('adminstrador', $this->session)):?>
								<label class="control-label">Usuario</label>
							<?php endif; ?>
								<div class="controls">
								<?php if(isset($trabajo['idTrabajos'])):?>
									<input type="hidden" name="idTrabajos" id="idTrabajos" value="<?php echo $trabajo['idTrabajos'];?>">
								<?php endif;?>

								<?php if(!checkRol('adminstrador', $this->session)):?>
									<input type="hidden" name="idUsuarios" id="idUsuarios" value="<?php echo $trabajo['idUsuarios'];?>" >
								<?php else:?>
									<select name="idUsuarios">
										<?php foreach($usuarios as $usuario):?>
											<?php if($usuario['idUsuarios'] == $trabajo['idUsuarios']):?>
												<option value="<?php echo $usuario['idUsuarios'];?>" selected="selected">
													<?php echo $usuario['email']. " - ". $usuario['nombre'] . " " .$usuario['apellido'];?>
												</option>
											<?php else:?>
												<option value="<?php echo $usuario['idUsuarios'];?>">
													<?php echo $usuario['email']. " - ". $usuario['nombre'] . " " .$usuario['apellido'];?>
												</option>
											<?php endif;?>
										<?php endforeach;?>
									</select>
								<?php endif;?>
							</div>
							<!-- TITULO -->
							<div class="control-group">&nbsp;</div>
							<label class="control-label">Titulo</label>
							<div class="controls">
								<input type="text" name="titulo" id="titulo" value="<?php if(isset($trabajo['titulo'])) echo $trabajo['titulo'];?>">
							</div>
							<!-- TEXTO -->
							<div class="control-group">&nbsp;</div>
							<label class="control-label">Texto</label>
							<div class="controls">
								<textarea name="texto" class="span6" rows="6"><?php if(isset($trabajo['texto'])) echo $trabajo['texto'];?></textarea>
							</div>
							<!-- RESUMEN -->
							<div class="controls">&nbsp;</div>
							<label class="control-label">Resumen </label>
							<div class="control-group">
								<textarea name="resumen" class="span6" rows="6"><?php if(isset($trabajo['resumen'])) echo $trabajo['resumen'];?></textarea>
							</div>
							<!-- FECHA -->
							<div class="controls">&nbsp;</div>
							<label class="control-label">Fecha &nbsp;</label>
							<div class="control-group">
								<input type="text" name="fecha" id="fecha" class="input-small datepicker" value="<?php if(isset($trabajo['fecha'])) echo date('d-m-Y',strtotime($trabajo['fecha']));?>" >
							</div>
							<?php if(checkRol('administrador', $this->session)):?>
								<!-- PALABRAS CLAVE -->
								<div class="controls">&nbsp;</div>
								<label class="control-label">Palabras clave&nbsp;</label>
								<div class="control-group">
									<input type="text" name="palabrasClave" id="palabrasClave" value="<?php if(isset($trabajo['palabrasClave'])) echo $trabajo['palabrasClave'];?>">
								</div>
								<!-- PRECIO SIN DERECHOS -->
								<div class="controls">&nbsp;</div>
								<label class="control-label">Precio Sin derechos&nbsp;</label>
								<div class="control-group">
									<input type="text" class="input-small" name="precio_sin_derecho" id="precio_sin_derecho" value="<?php if(isset($trabajo['precio_sin_derecho'])) echo $trabajo['precio_sin_derecho'];?>">
								</div>
								<!-- PRECIO CON DERECHOS -->
								<div class="controls">&nbsp;</div>
								<label class="control-label">Precio Con derechos&nbsp;</label>
								<div class="control-group">
									<input type="text" class="input-small" name="precio_con_derecho" id="precio_con_derecho" value="<?php if(isset($trabajo['precio_con_derecho'])) echo $trabajo['precio_con_derecho'];?>">
								</div>
								<!-- MONTO AL AUTOR EN CASO DE VENTA -->
								<div class="controls">&nbsp;</div>
								<label class="control-label">Monto al autor en caso de venta&nbsp;</label>
								<div class="control-group">
									<input type="text" class="input-small" name="monto_por_venta" id="monto_por_venta" value="<?php if(isset($trabajo['monto_por_venta'])) echo $trabajo['monto_por_venta'];?>">
								</div>
							<?php endif;?>
						<!-- INDICE -->
						<div class="controls">&nbsp;</div>
						<label class="control-label">Indice</label>
						<div class="controls">
							<textarea name="indice" class="span6" rows="6"><?php if(isset($trabajo['indice'])) echo $trabajo['indice'];?></textarea>
						</div>
						<?php if(checkRol('administrador', $this->session)):?>
							<!-- ARCHIVO PRIVADO -->
							<div class="controls">&nbsp;</div>
							<div class="formSep">
								<label class="control-label">Archivo privado<br> del trabajo &nbsp;</label>
								<div class="control-group">
									<input type="file" name="archivo_privado">
									<?php if(isset($trabajo['archivo_privado']) and !empty($trabajo['archivo_privado'])):?>
										<input type="hidden" name="ori_archivo_privado" value="<?php echo $trabajo['archivo_privado'];?>">
										<label class="uni-checkbox">
											<a href="<?php echo '/' . UPLOADS.  "trabajos/archivos_privado/" . $trabajo['archivo_privado'];?>" target="_blank">
												<?php echo $trabajo['archivo_privado'];?>
											</a>
											<?php if ($this->uri->segment(3) == 'editar'): ?> <!-- solamente en EDIT se puede eliminar el archivo -->
												<input type="checkbox" name="removearchivo_privado" value="<?php echo $trabajo['archivo_privado'];?>">
												Eliminar
											<?php endif ?>
										</label><br />
									<?php endif;?>
								</div>
							</div>
							<?php endif;?>
							<!-- ARCHIVO PUBLICO -->
							<div class="controls">&nbsp;</div>
							<div class="formSep">
								<label class="control-label">Archivo público<br> del trabajo &nbsp;</label>
								<div class="control-group">
									<input type="file" name="archivo_publico">
									<?php if(isset($trabajo['archivo_publico']) and !empty($trabajo['archivo_publico'])):?>
										<input type="hidden" name="ori_archivo_publico" value="<?php echo $trabajo['archivo_publico'];?>">
										<label class="uni-checkbox">
											<a href="<?php echo '/' . UPLOADS. "trabajos/archivos_publico/" . $trabajo['archivo_publico'];?>" target="_blank">
												<?php echo $trabajo['archivo_publico'];?>
											</a>
											<?php if ($this->uri->segment(3) == 'editar'): ?> <!-- solamente en EDIT se puede eliminar el archivo -->
												<input type="checkbox" name="removearchivo_publico" value="<?php echo $trabajo['archivo_publico'];?>">
												Eliminar
											<?php endif ?>
										</label><br />
									<?php endif;?>
								</div>
							</div>
							<!-- ARCHIVO VISTA PREVIA -->
							<div class="controls">&nbsp;</div>
							<div class="formSep">
								<label class="control-label">Archivo Vista Previa<br> del trabajo &nbsp;</label>
								<div class="control-group">
									<input type="file" name="archivo_vista_previa" />
									<?php if(isset($trabajo['archivo_vista_previa']) && !empty($trabajo['archivo_vista_previa'])):?>
										<input type="hidden" name="ori_archivo_vista_previa" value="<?php echo $trabajo['archivo_vista_previa'];?>">
										<label class="uni-checkbox">
											<a href="<?php echo '/' . UPLOADS. "trabajos/archivos_publico/" . $trabajo['archivo_vista_previa'];?>" target="_blank">
												<?php echo $trabajo['archivo_vista_previa'];?>
											</a>
											<?php if ($this->uri->segment(3) == 'editar'): ?> <!-- solamente en EDIT se puede eliminar el archivo -->
												<input type="checkbox" name="removearchivo_vista_previa" value="<?php echo $trabajo['archivo_vista_previa'];?>">
												Eliminar
											<?php endif; ?>
										</label><br />
									<?php endif;?>
								</div>
							</div>
							<!-- IMAGEN DE PORTADA -->
							<div class="controls">&nbsp;</div>
							<div class="formSep">
								<label class="control-label">Imagen de Portada&nbsp;</label>
								<div class="control-group">
									<input type="file" name="foto"><br>
									<?php if(isset($trabajo['foto']) and !empty($trabajo['foto'])):?>
										<input type="hidden" name="ori_foto" value="<?php echo $trabajo['foto'];?>">
										<label class="control-label">
											<a href="<?php echo '/' . UPLOADS. "trabajos/portadas/".$trabajo['foto'];?>" target="_blank">
												<?php echo $trabajo['foto']; ?>
											</a>
											<img align="left" src="<?php echo PUBLIC_FOLDER_FC. "trabajos/portadas/".$trabajo['foto'];?>" />
											<?php if ($this->uri->segment(3) == 'editar'): ?> <!-- solamente en EDIT se puede eliminar el archivo -->
												<input type="checkbox" name="removeFoto" value="<?php echo $trabajo['foto'];?>" />
												Eliminar
											<?php endif ?>
										</label> <br />
									<?php endif;?>
								</div>
							</div>
							<!-- DESTACADO -->
							<div class="controls">&nbsp;</div>
							<label class="control-label">Destacado&nbsp;</label>
							<div class="control-group">
								<select name="destacado" class="input-small">
									<option value="1" <?php if(isset($trabajo['destacado']) and $trabajo['destacado']) echo 'selected="selected"';?> >
										Si
									</option>
									<option value="0" <?php if(!isset($trabajo['destacado']) or $trabajo['destacado'] == 0) echo 'selected="selected"'?>>
										No
									</option>
								</select>
							</div>

							<?php /* ?>
							<div class="control-group">

								<select name="idCategorias[]" id="idCategorias" multiple="multiple" class="multiselect">
								<?php foreach($parentCat as $parent):?>
									<optgroup label="<?php echo $parent['nombreCategoria'];?>">
									<option value="<?php echo $parent['idCategorias'];?>"><?php echo $parent['nombreCategoria'];?></option>
									<?php foreach($categorias as $categoria):?>
										<?php if($categoria['parentId'] == $parent['idCategorias']):?>
											<option value="<?php echo $categoria['idCategorias'];?>"><?php echo $categoria['nombreCategoria'];?></option>
										<?php endif;?>
									<?php endforeach;?>
									</optgroup>
								<?php endforeach;?>
								</select>
							</div>
							<?php */ ?>
							<!-- CATEGORIA -->
							<div class="controls">&nbsp;</div>
							<label class="control-label">Categoría </label>
							<div class="control-group">
								<select name="idCategorias_parentId" id="idCategorias_parentId" >
									<?php foreach($categorias as $categoria):?>
										<?php if($categoria['parentId'] == 0):?>
											<?php if(isset($trabajo['idCategorias_parentId']) and $trabajo['idCategorias_parentId'] == $categoria['idCategorias']):?>
												<option value="<?php echo $categoria['idCategorias'];?>" selected="selected"><?php echo $categoria['nombreCategoria'];?></option>
											<?php else:?>
												<option value="<?php echo $categoria['idCategorias'];?>"><?php echo $categoria['nombreCategoria'];?></option>
											<?php endif?>
										<?php endif;?>
									<?php endforeach;?>
								</select>
							</div>
							<!-- SUBCATEGORIAS -->
							<div class="controls">&nbsp;</div>
							<label class="control-label">Subcategorías </label>
							<div class="control-group">
								<select name="idCategorias[]" multiple="multiple" id="idCategorias" >

								</select>
							</div>
							<!-- CANTIDAD DE PALABRAS -->
							<div class="controls">&nbsp;</div>
							<label class="control-label">Cantidad de palabras&nbsp;</label>
							<div class="control-group">
								<input type="text" name="cantidadPalabras" id="cantidadPalabras" class="input-small" value="<?php if(isset($trabajo['cantidadPalabras'])) echo $trabajo['cantidadPalabras'];?>" >
							</div>

							<!-- LISTA DE PRECIOS -->
							<!-- POR AHORA NO VA
							<div class="controls">&nbsp;</div>
							<label class="control-label">Lista de Precios&nbsp;</label>
							<div class="control-group">
								<select name="idPrecios">
									<?php foreach($precios as $precio):?>
										<?php if($precio['idPrecios'] == $trabajo['idPrecios']): ?>
											<option value="<?php echo $precio['idPrecios'];?>" selected="selected">
												<?php echo $precio['precio'];?>
											</option>
										<?php else:?>
											<option value="<?php echo $precio['idPrecios'];?>">
												<?php echo $precio['precio'];?>
											</option>
										<?php endif;?>
									<?php endforeach;?>
								</select>
							</div>
							 -->
							<?php if(checkRol('administrador', $this->session)):?>
								<div class="controls">&nbsp;</div>
								<label class="control-label">Estado&nbsp;</label>
								<div class="control-group">
									<select name="idEstados" class="input-small">
										<?php foreach($estados as $estado):?>
											<?php if(isset($trabajo['idEstados']) and $trabajo['idEstados'] == $estado['idEstados']):?>
												<option value="<?php echo $estado['idEstados'];?>" selected="selected"><?php echo $estado['estado'];?></option>
											<?php else:?>
												<option value="<?php echo $estado['idEstados'];?>" ><?php echo $estado['estado'];?></option>
											<?php endif;?>
										<?php endforeach;?>
									</select>
								</div>

								<div class="controls">&nbsp;</div>
								<label class="control-label">Notificar al Usuario&nbsp;</label>
								<div class="control-group">
									<select name="notificado" class="input-small">
										<option value="0" selected="selected">NO</option>
										<option value="1"> >SI</option>
									</select>
								</div>


							<?php endif;?>
							<div class="controls">&nbsp;</div>
						</div>
						<?php if(isset($errors)):?>
						<div class="alert alert-error">
							<?php echo $errors;?>
						</div>
						<?php endif;?>
						<?php if(isset($success)):?>
						<div class="alert alert-success">
							<?php echo $success;?>
						</div>
						<?php endif;?>
						<div class="controls">&nbsp;</div>
						<div class="control-group formSep">
							<button type="submit" class="btn">Guardar</button>
						</div>
					</fielset>
				</form>
			</div>
		</div>
	</div>
</div>



<script>
$(function(){
	$('.datepicker').datepicker({
		format: 'dd-mm-yyyy'
		});
	console.log(_public_folder);
	var idCategorias = $('#idCategorias_parentId :selected').val();
	<?php if(isset($trabajo['idTrabajos'])):?>
		var idTrabajos = <?php echo $trabajo['idTrabajos'];?>;
	<?php else:?>
		var idTrabajos = 0;
	<?php endif;?>

	$.ajax({

		url: _public_folder + 'categorias/getSubCategoriasOption/'+idCategorias,
		type: 'POST',
		data: {idTrabajos: idTrabajos},
		dataType: 'html',
		success: function(option) {
			//console.log(option);
			$('#idCategorias').html(option);
		},
		error: function(e) {
			//console.log(e);
		}
	});

	$('#idCategorias_parentId').live('change',function(){
		var parentId = $(this).val();

		$.ajax({
			url: _public_folder + 'categorias/getSubCategoriasOption/'+parentId,
			type: 'POST',
			data: {idTrabajos: idTrabajos},
			dataType: 'html',
			success: function(option) {
				//console.log(option);
				$('#idCategorias').html(option);
			},
			error: function(e) {
				//console.log(e);
			}
		});
	});


});
</script>
