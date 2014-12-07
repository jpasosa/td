<td id="cellBody">
	<h3>Subir nueva publicación </h3>
	<?php if (isset($errors)): ?>
		<div class="errors">
			<?php echo $errors; ?>
		</div>
	<?php endif ?>
	<form action="<?php echo $form_action; ?>" method="post" enctype="multipart/form-data" name="contentPublicar" target="_self" id="contentPublicarForm">
		<div id="contentPublicar">
			<p>Cargar una nueva publicación.</p>
			<div class="floatleft">
				<?php if (!$admin): ?>
					<input type="hidden" name="idUsuarios" value="<?php echo $id_user; ?>">
				<?php endif; ?>
				<label>Título (*)<br />
					<input type="text" name="titulo" value="<?php if (isset($work['titulo'])): echo $work['titulo']; endif; ?>" />
				</label>
				<br />
				<p style="width: 350px;">
					Importante: Cuanta mayor información se introduzca en la descripción y en el resumen,
					mayores ventas podrá obtener.
				</p>
				<label>Descripción breve (*)<br />
					<textarea name="texto" class="descripcion"><?php if (isset($work['texto'])): echo $work['texto']; endif; ?></textarea>
				</label>
				<br />
				<label>Resúmen<br />
					<textarea name="resumen" class="descripcion"><?php if (isset($work['resumen'])): echo $work['resumen']; endif; ?></textarea>
				</label>
				<br />
				<label>Cant. de páginas<br />
					<input type="text" name="cantidad_paginas" value="<?php echo $work['cantidad_paginas']; ?>"/>
				</label>
				<br />
				<label>Cant. de palabras<br />
					<input type="text" name="cantidadPalabras" value="<?php if (isset($work['cantidadPalabras'])): echo $work['cantidadPalabras']; endif; ?>" />
				</label>
				<br />
				<label>Palabras clave (separadas por comas)<br />
					<input type="text" name="palabrasClave" value="<?php if (isset($work['palabrasClave'])): echo $work['palabrasClave']; endif; ?>" />
				</label>
				<br />
				<!-- ARCHIVO PRIVADO -->
				<label>
					Documento (.doc, .docx, .odt, .pdf)<br />
					<!-- <span class="btn btn-file" style="font-size: 11px;" name="btSeleccionar" id="btSeleccionar" > -->
						Selecc
						<input type="file" name="archivo_privado" />
						<input type="hidden" name="name_privado" value="<?php if (isset($work['archivo_privado'])): echo $work['archivo_privado']; endif; ?>">
					<!-- </span> -->
					<!-- <input type="file" name="archivo_privado"> -->
				</label><br />

				<?php if(isset($work['archivo_privado']) && !empty($work['archivo_privado']) && !isset($valid_file_privado)):?>
					<!-- <input type="hidden" name="ori_archivo_privado" value="<?php echo $work['archivo_privado'];?>"> -->
					<a title="<?php echo $work['archivo_privado']; ?>" style="font-size: 9px;" href="<?php echo RAIZ . UPLOADS.  "trabajos/archivos_privado/" . $work['archivo_privado'];?>" target="_blank">
						<?php if (isset($work['archivo_privado_para_mostrar'])): echo $work['archivo_privado_para_mostrar']; endif; ?>
					</a>
				<?php endif;?>
					<!-- <input name="btSeleccionar" id="btSeleccionar" type="submit" value="Selecc." class="btn" /> -->
				<br/>
				<!-- ARCHIVO VISTA PREVIA PARA EDITORIALES -->
				<?php if (isset($is_editorial) && $is_editorial && !isset($valid_file_previa)): ?>
					<label>
						Documento Vista Previa (.doc, .docx, .odt)<br />
						<!-- <span class="btn btn-file" style="font-size: 11px;" name="btSeleccionar" id="btSeleccionar" > -->
							Selecc
							<input type="file" name="archivo_vista_previa" />
							<input type="hidden" name="name_vista_previa" value="<?php if (isset($work['archivo_vista_previa'])): echo $work['archivo_vista_previa']; endif; ?>">
						<!-- </span> -->
						<!-- <input type="file" name="archivo_privado"> -->
					</label><br />

					<?php if(isset($work['archivo_vista_previa']) && !empty($work['archivo_vista_previa'])):?>
						<!-- <input type="hidden" name="ori_archivo_privado" value="<?php echo $work['archivo_vista_previa'];?>"> -->
						<a title="<?php echo $work['archivo_vista_previa']; ?>" style="font-size: 9px;" href="<?php echo RAIZ . UPLOADS.  "trabajos/archivos_vista_previa/" . $work['archivo_vista_previa'];?>" target="_blank">
							<?php if (isset($work['vista_previa_mostrar'])): echo $work['vista_previa_mostrar']; endif; ?>
						</a>
					<?php endif;?>
						<!-- <input name="btSeleccionar" id="btSeleccionar" type="submit" value="Selecc." class="btn" /> -->
					<br/>
				<?php endif; ?>


				<?php if ($admin): ?>
					<hr />
					<p> Opciones del Administrador </p>
					<label>Usuario (*)  <br />
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
					</label>
					<br />
					<label>Fecha (*)
						<input type="text" id="fecha" name="fecha" class="campocorto" value="<?php echo $work['fecha']; ?>"/>
					</label>
					<p>
						Destacado (Si/No)
						<select name="destacado" class="campocorto">
							<option value="1" <?php if(isset($trabajo['destacado']) && $trabajo['destacado']) echo 'selected="selected"';?> >
								Si
							</option>
							<option value="0" <?php if(!isset($trabajo['destacado']) || $trabajo['destacado'] == 0) echo 'selected="selected"'?>>
								No
							</option>
						</select>
					</p>
					<br />
					<label>Documento (vers. Pública)(.doc, .docx, .odt) (*)	<br />
						<!-- <span class="btn btn-file" style="font-size: 11px;" name="btSeleccionar" id="btSeleccionar" > -->
							Selecc
							<input type="file" name="archivo_publico"  />
						<!-- </span> -->
						<!-- <input type="file" name="archivo_publico" /> -->
					</label><br />
					<?php if(isset($work['archivo_publico']) && !empty($work['archivo_publico'])):?>
						<input type="hidden" name="ori_archivo_privado" value="<?php echo $work['archivo_publico'];?>">
						<a style="font-size: 9px;" href="<?php echo '/' . UPLOADS.  "trabajos/archivos_privado/" . $work['archivo_publico'];?>" target="_blank">
							<?php echo $work['archivo_publico'];?>
						</a>
					<?php endif;?>
					<!-- <input name="btSeleccionar" id="btSeleccionar" type="submit" value="Selecc." class="btn" /> -->
					<br />
					<label>Notificar al Usuario <br />
						<select name="notificado">
							<option value="0" selected="selected">NO</option>
							<option value="1">SI</option>
						</select>
					</label>
					<br />
				<?php endif ?>
			</div>

			<div class="floatright">
				<label>Imagen de portada (.jpg, .jpeg, .png) <br />
					<!-- <span class="btn btn-file" style="font-size: 11px;" name="btSeleccionar" id="btSeleccionar" > -->
						Selecc
						<input type="file" name="foto"  />
						<input type="hidden" name="name_foto" value="<?php if (isset($work['foto'])): echo $work['foto']; endif; ?>">
					<!-- </span> -->
					<!-- <input type="file" name="archivo_privado"> -->
				</label><br />
				<?php if(isset($work['foto']) && !empty($work['foto'])):?>
					<input type="hidden" name="name_foto" value="<?php echo $work['foto'];?>">
					<a style="font-size: 9px;" href="<?php echo '/' . UPLOADS.  "trabajos/archivos_privado/" . $work['foto'];?>" target="_blank">
						<?php echo $work['foto'];?>
					</a>
				<?php endif;?>
				<!-- <input name="btSeleccionar" id="btSeleccionar" type="submit" value="Selecc." class="btn"/> -->
				<br/>
				<label>Indice (Pegar en el campo) <br />
					<textarea id="inputindice" name="indice"><?php echo $work['indice']; ?></textarea>
				</label>
				<br/>
				<p>Categoría <br />
					<select name="idCategorias_parentId" id="idCategorias_parentId" >
						<?php foreach($categorias as $categoria):?>
							<?php if($categoria['parentId'] == 0):?>
								<?php if(isset($work['idCategorias_parentId']) and $work['idCategorias_parentId'] == $categoria['idCategorias']):?>
									<option value="<?php echo $categoria['idCategorias'];?>" selected="selected"><?php echo $categoria['nombreCategoria'];?></option>
								<?php else:?>
									<option value="<?php echo $categoria['idCategorias'];?>"><?php echo $categoria['nombreCategoria'];?></option>
								<?php endif?>
							<?php endif;?>
						<?php endforeach;?>
					</select>
				</p>
				<p>Subcategorias <br />
					<select name="subCategorys[]" multiple="multiple" id="idCategorys" style="height: 50px;" >
						<?php if (count($work['sub_category']) > 0): ?>
								<?php foreach ($work['sub_category'] as $key => $sub_cat): ?>
									<option value="<?php echo $sub_cat['id']; ?>" selected="selected"><?php echo $sub_cat['name']; ?> <option>
								<?php endforeach ?>
						<?php endif ?>
					</select>
				</p>
				<p>Nível <br />
					<select name="nivel" id="nivel" >
						<?php foreach($niveles AS $nivel):?>
							<option value="<?php echo $nivel;?>" <?php if($trabajo['nivel'] == $nivel) echo 'selected="selected"'; ?> >
								<?php echo $nivel;?>
							</option>
						<?php endforeach;?>
					</select>
				</p>


				<!--
				<label>Lista de Precios  <br />
					<select name="idPrecios">
						<?php foreach($precios as $precio):?>
							<?php if($precio['idPrecios'] == $work['idPrecios']):?>
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
				</label>
 				-->

				<?php if ($admin): ?>
				<hr/>
				<p> &nbsp; <p/>
					<label>Precio sin derechos (*)<br />
						<input type="text" name="precio_sin_derecho" value="<?php echo $work['precio_sin_derecho']; ?>" />
					</label>
					<br />
					<label>Precio con derechos (*)<br />
						<input type="text" name="precio_con_derecho" value="<?php echo $work['precio_con_derecho']; ?>" />
					</label>
					<br />
					<label>Monto al Autor en caso de venta (*)<br />
						<input type="text" name="monto_por_venta" value="<?php echo $work['monto_por_venta']; ?>" />
					</label>
					<p>Estado  <br />
						<select name="idEstados" >
							<?php foreach($estados as $estado):?>
								<?php if(isset($trabajo['idEstados']) and $trabajo['idEstados'] == $estado['idEstados']):?>
									<option value="<?php echo $estado['idEstados'];?>" selected="selected"><?php echo $estado['estado'];?></option>
								<?php else:?>
									<option value="<?php echo $estado['idEstados'];?>" ><?php echo $estado['estado'];?></option>
								<?php endif;?>
							<?php endforeach;?>
						</select>
					</p>

				<?php endif ?>

				<br />
			</div>
		</div>
		<p>
			<input name="btGuardar" id="btGuardar" type="submit" value="Guardar cambios" class="btn"/>
		</p>
	</form>
</td>



