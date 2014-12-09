<td id="cellBody">
	<div class="breadcrum">
		Ubicación:
		<a href="<?=site_url('temas/listar');?>">Temas</a> >
		<a href="<?=site_url('temas/ver/' . $work['categoria']['id']);?>">
			<?php echo $work['categoria']['nombre']; ?>
		</a>
		<!-- Si existen sub-categorias -->
		<?php if ($work['sub_categoria'] != NULL): ?>
			>
			<?php foreach($work['sub_categoria'] AS $sub_cat): ?>
				<a href="<?=site_url('temas/ver/' . $sub_cat['id']);?>">
					<?php echo $sub_cat['nombre'] . ' '; ?>
				</a>
			<?php endforeach; ?>
		<?php endif ?>
		>
		<?php echo $work['titulo']; ?>
	</div>
	<h3><?php echo $work['titulo'] ?></h3>
	<div class="clearfix">
		<div class="articuloDatosAutor">
			<div class="imagenarticulo">
				<?php if ($work['foto'] == ''): ?>
					<img src="<?php echo ASSETS . 'style/images/default_cover.png'; ?>" alt="Autores de WordRev" name="imagenarticulo" width="146" height="146" />
				<?php else: ?>
					<img src=" <?php echo UPLOADS . 'trabajos/portadas/' . $work['foto']; ?>" name="imagenarticulo" width="146" height="146" />
				<?php endif ?>
			</div>
			<p>Por:</p>

			<div class="nombre">
				<a href="#">
					<?php if (isset($work['autor']['avatar']) && $work['autor']['avatar'] == ''): ?>
						<img src="<?php echo ASSETS . 'style/images/default_avatar_thumb.png'; ?>" alt="Autor" width="52" height="30" />
					<?php elseif(isset($work['autor']['avatar']) && $work['autor']['avatar'] != ''): ?>
						<img src="<?php echo UPLOADS . 'usuarios/avatar/' . $work['autor']['avatar'] ?>" alt="Autor" width="52" height="30" />
					<?php endif; ?>
					<?php if (isset($work['autor']['nombre_mostrar']) ): ?>
						<a href="<?=site_url('tucuenta/perfil/' . $work['autor']['idUsuarios']);?>">
							<?php echo $work['autor']['nombre_mostrar']; ?>
						</a>
					<?php endif ?>
				</a>
			</div>
			<p class="italicas">
				<?php if (isset($work['autor']['profesion'])): ?>
					<?php echo $work['autor']['profesion']; ?>
				<?php endif ?>

			</p>
			<p>Ha publicado <?php echo $work['autor']['publicados'] ?> trabajos.</p>
		</div>

		<div class="articuloDetalles">
			<div class="favorito">
				<?php if ($login_user && !$is_favorito): ?>
					<a class="add_favoritos" id_user="<?php echo $id_user; ?>" id_trabajo="<?php echo $work['idTrabajos']; ?>" href="#" title="Agregar a Favoritos">
						<img class="add_favorito" src="<?php echo ASSETS . 'style/images/favorito.png'; ?>" />
					</a>
				<?php elseif ($login_user && $is_favorito): ?>
					<img src="<?php echo ASSETS . 'style/images/mostrar_favorito.png'; ?>" />
				<?php endif; ?>
			</div>
			<div class="indexacion">
				<p>
					Sección:
					<span class="resaltado">
						<a href="<?=site_url('temas/ver/' . $work['categoria']['id']);?>">
							<?php echo $work['categoria']['nombre']; ?>
						</a>
					</span>
				</p>
				<?php if ($work['sub_categoria'] != NULL): ?>
					<p>
						Subsección:
						<span class="resaltado">
							<?php foreach ($work['sub_categoria'] as $key => $sub_cat): ?>
								<a href="<?=site_url('temas/ver/' . $sub_cat['id']);?>">
									<?php echo $sub_cat['nombre'] . ' '; ?>
								</a>
							<?php endforeach; ?>
						</span>
					</p>
				<?php endif; ?>
				<p>
					Páginas:
					<span class="resaltado">
						<?php echo $work['cantidad_paginas']; ?>
					</span>
				</p>
				<p>
					Palabras clave:
					<span class="resaltado"><?php echo $work['palabrasClave']; ?></span>
				</p>
				<p>
					Vista previa:
					<span class="resaltado">
						<?php if (isset($vista_previa) && $vista_previa): ?>
							<a href="<?php echo RAIZ . UPLOADS . 'trabajos/archivos_vista_previa/' . $work['archivo_vista_previa']; ?>">Abrir</a>
						<?php else: ?>
							No disponible.
						<?php endif; ?>

					</span>
				</p>
				<p>Popularidad: <span class="resaltado"><?php echo $work['popularidad']; ?>%</span></p>
			</div>

			<div class="informacion">
				<p class="resaltado">Resúmen</p>
				<p><?php echo $work['resumen']; ?><br /></p>
				<p class="resaltado">Indice de contenidos</p>
				<!-- <ol>
					<ol>
						<li> Cómo identificar los encabezados para el índice de contenidos</li>
						<li> Cómo colocar el ínudice de contenidos</li>
						<li> Creación o actualización de un índice de contenidos</li>
						<li> Actualización de un índice de contenidos
							<ol>
								<li> Actualización a trav&eacute;s de la ventana para la creación del índice</li>
								<li>Actualización directamente desde el texto</li>
							</ol>
						</li>
						<li>  Creación de un índice de contenidos con enlaces</li>
						<li>Eliminación de un índice de contenidos</li>
						<li> Aspecto del índice de contenidos</li>
					</ol>
				</ol> -->
				<pre><?php echo $work['indice']; ?></pre>
			</div>
		</div>
	</div>
	<p class="precio">Precio: U$D <?php echo $work['precio_con_derecho']; ?></p>
	<p class="btn" id="btcomprar">
		<a href="<?=site_url($comprar_articulo);?>">
			Conseguir este Artículo
		</a>
	</p>
</td>
