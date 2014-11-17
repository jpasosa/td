<td id="cellBody">
	<div class="breadcrum">
		Ubicación:
		<a href="<?=site_url('temas/listar');?>">Temas</a>
		>
		<a href="<?=site_url('temas/ver/' . $works_by_category[0]['categoria']['id']);?>">
			<?php echo $works_by_category[0]['categoria']['nombre']; ?>
		</a>
		<?php if ($is_subcategory): ?>
			<?php foreach ($works_by_category[0]['sub_categoria'] as $key => $sc): ?>
					<?php if (isset($sc['seleccionada']) && $sc['seleccionada']): ?>
						>
						<a href="<?=site_url('temas/ver/' . $sc['id']);?>">
							<?php echo $sc['nombre']; ?>
						</a>
					<?php endif; ?>
			<?php endforeach; ?>
		<?php endif; ?>
		<?php if (isset($works_by_category[0]['sub_categoria']['id'])): ?>

			>
			<a href="<?=site_url('temas/ver/' . $works_by_category[0]['sub_categoria']['id']);?>">
				<?php echo $works_by_category[0]['sub_categoria']['nombre']; ?>
			</a>

		<?php endif; ?>
	</div>
	<h3> Publicaciones </h3>

	<!-- INICIO Presentacion de CATEGORIA //-->
	<div class="temasSeccion">
		<?php if ($category_image != ''): ?>
			<div class="imagenarticulo">
				<img src="<?php echo RAIZ . UPLOADS . 'categorias/iconos/' . $category_image; ?>" alt="Categoria de WordRev" name="Imagen Categoria" width="146" height="146" />
			</div>
		<?php else: ?>
			<div class="imagenarticulo">
				<img src="<?php echo ASSETS . 'style/images/imagen_categoria_default.png' ; ?>" alt="Categoria de WordRev" name="imagenarticulo" width="146" height="146" />
			</div>
		<?php endif; ?>
		<p>
			Sección:
			<span class="resaltado">
				<a href="<?=site_url('temas/ver/' . $category_id);?>">
					<?php echo $category_name; ?>
				</a>
			</span>
		</p>
	</div>

	<!-- INICIO Grupo de articulos articulo //-->
	<div class="temasArticulosEnCat clearfix">
		<?php foreach ($works_by_category AS $key => $tem): ?>
			<!-- INICIO Presentacion de articulo //-->
			<div class="temasDetallesArticulo">
				<div class="imagenarticulosmall">
					<?php if ($tem['foto'] == ''): ?>
						<img src="<?php echo ASSETS . 'style/images/default_cover.png'; ?>" alt="Portada del articulo" name="imagenarticulo" width="98" height="98" />
					<?php else: ?>
						<img src=" <?php echo UPLOADS . 'trabajos/portadas/' . $tem['foto']; ?>" name="imagenarticulo" width="98" height="98" />
					<?php endif ?>
				</div>
				<div class="indexacion">
					<p class="temasTitulo">
						<a href="<?=site_url('trabajos/ver/' . $tem['idTrabajos']);?>">
							<?php echo $tem['titulo']; ?>
						</a>
					</p>
					<p>
						<?php if ($tem['sub_categoria'] != NULL): ?>
							Subsección:
							<span class="resaltado">
								<?php foreach ($tem['sub_categoria'] AS $sc): ?>
									<a href="<?=site_url('temas/ver/' . $sc['id']);?>">
										<?php echo $sc['nombre']; ?>
									</a>
								<?php endforeach ?>
							</span>
						<?php endif; ?>
					</p>
				</div>

				<div class="informacion2">
					<p>Páginas: <?php echo $tem['cantidad_paginas']; ?></p>
					<p>
						Palabras clave:<span class="resaltado"><?php echo $tem['palabrasClave'] ?></span>
					</p>
				</div>

				<div class="informacion">
					<p class="resaltado">Descripción</p>
					<p>
						<?php echo $tem['resumen']; ?>
					</p>
				</div>
			</div>
			<!-- FIN Presentacion de articulo //-->
		<?php endforeach ?>
		<div id="paginador_categorias">
			<?php echo $paginador; ?>
		</div>
	</div>
	<!-- INICIO Grupo de articulos articulo //-->
	<!-- FINAL Presentacion de CATEGORIA //-->

</td>
