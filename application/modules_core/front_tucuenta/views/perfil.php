
<td id="cellBody">
	<div class="perfilDatosAutor">
		<h3>Sobre el Autor </h3>
		<p>
			<?php if ($perfil_and_works['usuario']['avatar'] == ''): ?>
				<img src="<?php echo ASSETS . 'style/images/default_avatar.png'; ?>" name="imagenarticulo" alt="Foto del Autor" width="285" height="166" />
			<?php else: ?>
				<img src=" <?php echo UPLOADS . 'usuarios/avatar/' . $author['avatar']; ?>"  width="285" height="166" />
			<?php endif ?>
		</p>
		<h2>
			<?php echo $perfil_and_works['usuario']['apellido'] . '  ' . $perfil_and_works['usuario']['nombre']; ?>
		</h2>
		<p>
			<?php echo $perfil_and_works['usuario']['intereses']; ?>
		</p>
		<p>
			Fecha de nacimiento:<br />
			<?php echo $perfil_and_works['usuario']['fecha']; ?>
		</p>
		<p>
			Profesión:<br />
			<?php echo $perfil_and_works['usuario']['profesion']; ?>
		</p>
		<p>
			Intereses: <br />
			<?php echo $perfil_and_works['usuario']['intereses']; ?>
		</p>
	</div>
	<p>&nbsp;</p>
	<div id="publicacionesAutor">
		<h3>Trabajos Publicados</h3>
			<?php if (isset($perfil_and_works['publicaciones']) && count($perfil_and_works['publicaciones']) > 0): ?>
				<?php foreach ($perfil_and_works['publicaciones'] AS $work): ?>
					<table id="tablaAutorPublicaciones" border="0" cellpadding="0" cellspacing="0">
						<tr>
							<?php $id_category = $work[0]['idCategorias_parentId']; ?>
							<td class="celdaFoto">
								&nbsp;
								<?php if (isset($perfil_and_works['publicaciones'][$id_category][0]['categoria']['imagen']) &&
											$perfil_and_works['publicaciones'][$id_category][0]['categoria']['imagen'] != '' ): ?>
									<img class="thumb" src="<?php echo RAIZ . UPLOADS . 'categorias/iconos/thumbs/' . $perfil_and_works['publicaciones'][$id_category][0]['categoria']['imagen']; ?>"
										alt="Categoria de WordRev" name="imagenarticulo" width="70" height="70" />
								<?php endif; ?>

							</td>
							<td class="celdaListado" rowspan="2">
								<?php foreach ($perfil_and_works['publicaciones'][$id_category] as $w): ?>
										<?php $name_category = $w['categoria']['nombre']; ?>
										<p>
											<a href="<?=site_url('trabajos/ver/' . $w['idTrabajos']);?>">
												<?php echo $w['titulo']; ?>
											</a>
										</p>
										<p>
											<?php if ($w['sub_categoria'] != NULL): ?>
												<!-- <span>(</span> -->
												<?php foreach ($w['sub_categoria'] AS $sc): ?>
													<a class="perfil_subcategorias" href="<?=site_url('temas/ver/' . $sc['id']);?>">
														<?php echo $sc['nombre']; ?>
													</a>
													<?php if ($sc['ultima'] == TRUE): ?>
														<!-- ) -->
														&nbsp;&nbsp;
													<?php endif ?>
												<?php endforeach ?>
											<?php endif; ?>
										</p>
								<?php endforeach ?>
								<a href="<?=site_url('tucuenta/perfilVerMas/' . $perfil_and_works['usuario']['idUsuarios'] . '/' . $id_category . '/pagina/1'); ?>">
									<span style="font-size: 11px; font-weight: normal;">VER MÁS</span>
								</a>
							</td>

						</tr>

						<tr>
							<td class="celdaCategoria">
								<h1>
									<a href="<?=site_url('temas/ver/' . $id_category);?>">
										<?php echo $name_category; ?>
									</a>
								</h1>

							</td>
						</tr>
					</table>
				<?php endforeach; ?>
			<?php else: ?>
				No existen publicaciones de este usuario.
			<?php endif; ?>
		<p>&nbsp;</p>
	</div>
</td>
