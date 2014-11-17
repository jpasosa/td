<td id="cellBody">
	<div class="breadcrum">
		Ubicación:
		<a href="<?=site_url('temas/listar');?>">Temas</a>
	</div>
	<h3> Publicaciones </h3>


	<?php foreach ($all_topics_to_show AS $k_topics => $topics): ?>

		<!-- INICIO Presentacion de CATEGORIA //-->
		<div class="temasSeccion">
			<div class="imagenarticulo">
				<?php if ($all_topics_to_show[$k_topics][0]['categoria']['imagen'] != ''): ?>
					<img src="<?php echo RAIZ . UPLOADS . 'categorias/iconos/' . $all_topics_to_show[$k_topics][0]['categoria']['imagen']; ?>" alt="Categoria de WordRev"
							name="imagenarticulo" width="146" height="146" />
				<?php else: ?>
					<img src="<?php echo ASSETS . 'style/images/imagen_categoria_default.png' ; ?>" alt="Categoria de WordRev"
							name="imagenarticulo" width="146" height="146" />
				<?php endif; ?>
			</div>
			<p>
				Sección:
				<span class="resaltado">
					<?php $id_category = $all_topics_to_show[$k_topics][0]['categoria']['id']; ?>
					<a href="<?=site_url('temas/ver/' . $all_topics_to_show[$k_topics][0]['categoria']['id']);?>">
						<?php echo $all_topics_to_show[$k_topics][0]['categoria']['nombre']; ?>
					</a>
				</span>
			</p>
		</div>

		<!-- INICIO Grupo de articulos articulo //-->
		<div class="temasArticulosEnCat clearfix">
			<?php foreach ($all_topics_to_show[$k_topics] AS $key => $tem): ?>
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
		</div>
		<!-- INICIO Grupo de articulos articulo //-->
		<!-- FINAL Presentacion de CATEGORIA //-->
		<div class="paginador">
			<a href="<?=site_url('temas/listarPorCategoria/' . $id_category . '/pagina/1');?>">
				Ver más en esta sección
			</a>
		</div>











	<?php endforeach ?>



</td>
