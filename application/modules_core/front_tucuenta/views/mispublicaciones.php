<td id="cellBody">

	<h3> Mis Publicaciones </h3>
	<div class="temasSeccion">
		<div class="imagenarticulo">
			<?php if ($author['avatar'] == ''): ?>
				<img src="<?php echo ASSETS . 'style/images/default_avatar.png'; ?>" name="imagenarticulo" alt="Foto del Autor" width="146" height="146" />
			<?php else: ?>
				<img src=" <?php echo UPLOADS . 'usuarios/avatar/' . $author['avatar']; ?>"  width="146" height="146" />
			<?php endif ?>
		</div>
	</div>
	<!-- INICIO Grupo de articulos articulo //-->
	<div class="temasArticulosEnCat clearfix">

		<?php if (count($works_of_author['publicacion']) > 0): ?>
			<?php foreach ($works_of_author['publicacion'] AS $work): ?>
				<div class="misArticulos">
					<div class="imagenarticulosmall">
						<?php if ($work['foto'] == ''): ?>
							<img src="<?php echo ASSETS . 'style/images/default_cover_thumb.png'; ?>" name="imagenarticulo" alt="imágen de la publicación" name="imagenarticulo" width="98" height="98" />
						<?php else: ?>
							<img src=" <?php echo UPLOADS . 'trabajos/portadas/' . $work['foto']; ?>"  width="98" height="98" />
						<?php endif ?>
					</div>
					<div class="indexacion">
						<p class="temasTitulo">
							<a href="<?php echo site_url('trabajos/editar/' . $work['idTrabajos']);?>">
								<?php echo $work['titulo']; ?>
							</a>
						</p>
						<p>
							Sección:
							<span class="resaltado"><?php echo $work['categoria']['nombre']; ?></span>
						</p>
						<?php if ($work['sub_categoria'] != NULL): ?>
							<p>
								Subsección:
								<span class="resaltado">
									<?php foreach ($work['sub_categoria'] as $key => $sub_cat): ?>
										<?php echo $sub_cat['nombre']; ?>
									<?php endforeach ?>
								</span>
							</p>
						<?php endif; ?>
					</div>
					<div class="informacion2">
						<p>Páginas: <?php echo $work['cantidad_paginas']; ?></p>
						<p>
							Palabras clave:
							<span class="resaltado"><?php echo $work['palabrasClave']; ?></span>
						</p>
						<!-- POR AHORA NO VA EL ELIMINAR
						<p>
							<a class="del_publicacion" id="<?php echo $work['idTrabajos']; ?>" href="#" style="font-size: 11px;"> ELIMINAR </a>
						</p> -->
					</div>
				</div>
			<?php endforeach; ?>

			<div class="paginador"><?php echo $paginador; ?></div>
		<?php else: ?>
			Este autor, aún no tiene publicaciones.
		<?php endif; ?>
	</div>
	<!-- FINAL Presentacion de CATEGORIA //-->
</td>



